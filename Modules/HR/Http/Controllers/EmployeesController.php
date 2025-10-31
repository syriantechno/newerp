<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\HR\Models\{Employee, Document, Company, Department, Designation};
use Modules\HR\Http\Requests\EmployeeRequest;

class EmployeesController extends Controller
{
    public function index(Request $req)
    {
        return view('hr::employees.index');
    }

    public function table(Request $req)
    {
        $q = Employee::query()
            ->with(['company','department','designation'])
            ->when($req->filled('status'), fn($x)=>$x->where('status',$req->status))
            ->when($req->filled('company_id'), fn($x)=>$x->where('company_id',$req->company_id))
            ->when($req->filled('search'), function($x) use ($req){
                $s = '%'.$req->search.'%';
                $x->where(function($y) use ($s){
                    $y->where('name','like',$s)->orWhere('emp_code','like',$s)->orWhere('email','like',$s);
                });
            })
            ->latest('id');

        $employees = $q->paginate(20);
        return view('hr::employees._table', compact('employees'));
    }

    public function store(EmployeeRequest $req)
    {
        $data = $req->validated();
        if (isset($data['extra']) && is_string($data['extra'])) {
            $json = json_decode($data['extra'], true);
            $data['extra'] = $json ?: null;
        }
        $emp = Employee::create($data);
        return response()->json(['ok'=>true, 'id'=>$emp->id]);
    }

    public function show(Employee $employee)
    {
        $employee->load(['company','department','designation','documents']);
        return response()->json($employee);
    }

    public function update(EmployeeRequest $req, Employee $employee)
    {
        $data = $req->validated();
        if (isset($data['extra']) && is_string($data['extra'])) {
            $json = json_decode($data['extra'], true);
            $data['extra'] = $json ?: null;
        }
        $employee->update($data);
        return response()->json(['ok'=>true]);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['ok'=>true]);
    }

    public function uploadDocument(Request $req, Employee $employee)
    {
        $req->validate([
            'type'=>'required|string',
            'file'=>'required|file|max:10240',
            'issued_at'=>'nullable|date',
            'expires_at'=>'nullable|date',
            'number'=>'nullable|string'
        ]);

        $path = $req->file('file')->store('hr/docs','public');
        $employee->documents()->create([
            'type'=>$req->type,
            'file_path'=>$path,
            'issued_at'=>$req->issued_at,
            'expires_at'=>$req->expires_at,
            'number'=>$req->number,
        ]);

        return response()->json(['ok'=>true]);
    }

    public function deleteDocument(Employee $employee, Document $doc)
    {
        abort_unless($doc->documentable_id === $employee->id && $doc->documentable_type === Employee::class, 404);
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
        return response()->json(['ok'=>true]);
    }

    public function export(string $type)
    {
        abort_unless(in_array($type,['csv']), 404);
        $rows = Employee::with(['company','department','designation'])->get();

        $csv = implode(",", ['ID','Code','Name','Email','Phone','Company','Department','Designation','Status'])."\n";
        foreach($rows as $r){
            $csv .= implode(",", [
                $r->id, $r->emp_code, '"'.$r->name.'"', $r->email, $r->phone,
                optional($r->company)->name, optional($r->department)->name, optional($r->designation)->name, $r->status
            ])."\n";
        }
        return response($csv,200,[
            'Content-Type'=>'text/csv',
            'Content-Disposition'=>'attachment; filename=employees.csv'
        ]);
    }
}
