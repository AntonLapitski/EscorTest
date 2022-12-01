<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 29.11.22
 * Time: 15.03
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{

    public function registerManager(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|unique:users',
            'password' => 'required|max:10',
            'role' => 'required'
        ]);


        $user = new User();
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = $request->get('password');
        $user->role = $request->get('role');
        $user->save();

       return response()->json([
            'message' => 'Manager created successfully',
            'status' => '200',
        ]);
    }

    public function loginManager(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

        $user = User::where('email', $request->email)->first();

        Auth::guard('api')->loginUsingId($user->id, true);

        return response()->json([
            'message' => 'User logged in',
            'status' => '200',
        ]);
    }

    public function loginEmployee(Request $request)
    {
        $validateEmployee = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

        $employee = Employee::where('email', $request->email)->first();

        Auth::guard('employee')->loginUsingId($employee->id, true);

        return response()->json([
            'message' => 'Employee logged in',
            'status' => '200',
        ]);
    }

    public function createEmployee(Request $request)
    {
        if (!Auth::guard('api')->check()){
            throw new \Exception('User not logged');
        }

        if (Auth::guard('api')->user()->cannot('create', User::class)) {
            abort(403);
        }

        $validateEmployee = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

        $employee = new Employee();
        $employee->email = $request->get('email');
        $employee->password = $request->get('password');
        $employee->manager_id = auth('api')->user()->id;
        $employee->role = 'employee';
        $employee->save();

        return response()->json([
            'message' => 'Employee created',
            'status' => '200',
        ]);
    }

    public function showRecords(Request $request)
    {
        if (!Auth::guard('employee')->check()){
            throw new \Exception('User not logged');
        }

        if (Auth::guard('employee')->user()->cannot('view', Employee::class)) {
            abort(403);
        }

        $validateEmployee = Validator::make($request->all(),
            [
                'id' => 'required'
            ]);

        $employee = Employee::find($request->get('id'));

        return response()->json($employee->records()->paginate(10));
    }

    public function createRecord(Request $request)
    {
        if (!Auth::guard('employee')->check()){
            throw new \Exception('Employee not logged');
        }

        if (Auth::guard('employee')->user()->cannot('create', Employee::class)) {
            abort(403);
        }

        $validateRecord = Validator::make($request->all(),
            [
                'name' => 'required',
                'picture' => 'required',
                'category_id' => 'required'
            ]);

        $record = new Record();
        $record->name = $request->get('name');
        $record->picture = $request->get('picture');
        $record->category_id = $request->get('category_id');
        $record->employee_id = $request->get('employee_id');
        $record->save();

        return response()->json([
            'message' => 'Record created',
            'status' => '200',
        ]);
    }

    public function deleteRecordByManager(Request $request)
    {
        if (!Auth::guard('api')->check()){
            throw new \Exception('User not logged');
        }

        if (Auth::guard('api')->user()->cannot('delete', User::class)) {
            abort(403);
        }

        $validateRecord = Validator::make($request->all(),
            [
                'employee_id' => 'required',
                'record_id' => 'required',
            ]);

        $id_manager = Auth::guard('api')->id();
        $id_employee = $request->get('employee_id');
        $id_record = $request->get('record_id');

        $employee = Employee::find($id_employee);
        $boolresult = $id_manager === $employee->manager_id;
        $record = Record::where('employee_id', '=', $id_employee)->get();

        if ($boolresult && $record) {
            Record::destroy($id_record);
        }else {
            throw new \Exception('Something went wrong');
        }

        return response()->json(['status' => '200', 'message' => 'Record deleted']);
    }

    public function deleteRecordByEmployee(Request $request)
    {
        if (!Auth::guard('employee')->check()){
            throw new \Exception('Employee not logged');
        }

        if (Auth::guard('employee')->user()->cannot('delete', Employee::class)) {
            abort(403);
        }

        $validateRecord = Validator::make($request->all(),
            [
                'record_id' => 'required',
            ]);

        $id_employee= Auth::guard('api')->id();
        $id_record = $request->get('record_id');
        $record = Record::find($id_record);
        $boolresult = $id_employee === $record->employee_id;

        if ($boolresult) {
            Record::destroy($id_record);
        }else {
            throw new \Exception('Something went wrong');
        }

        return response()->json(['status' => '200', 'message' => 'Record deleted']);
    }

    public function updateRecordByEmployee(Request $request)
    {
        if (!Auth::guard('employee')->check()){
            throw new \Exception('Employee not logged');
        }

        if (Auth::guard('employee')->user()->cannot('update', Employee::class)) {
            abort(403);
        }

        $validateRecord = Validator::make($request->all(),
            [
                'record_id' => 'required',
            ]);

        $id_employee= Auth::guard('api')->id();
        $id_record = $request->get('record_id');
        $record = Record::find($id_record);
        $boolresult = $id_employee === $record->employee_id;

        if ($boolresult) {
            $record->name = $request->get('name');
            $record->picture = $request->get('picture');
            $record->employee_id = $request->get('employee_id');
            $record->category_id = $request->get('category_id');
            $record->save();
        }else {
            throw new \Exception('Something went wrong');
        }

        return response()->json(['status' => '200', 'message' => 'Record updated']);
    }

    public function getManagerEmployeeRecords(Request $request)
    {
        if (!Auth::guard('api')->check()){
            throw new \Exception('User not logged');
        }

        if (Auth::guard('api')->user()->cannot('show', User::class)) {
            abort(403);
        }

        $validateRecord = Validator::make($request->all(),
            [
                'employee_id' => 'required',
            ]);

        $employee = Employee::find($request->get('employee_id'));

        return response()->json($employee->records()->paginate(10));
    }

    public function getByManagerAllEmployeeRecords()
    {
        if (!Auth::guard('api')->check()){
            throw new \Exception('User not logged');
        }

        if (Auth::guard('api')->user()->cannot('getRecords', User::class)) {
            abort(403);
        }

        $id_manager = Auth::guard('api')->id();
        $manager = User::find($id_manager);
        $employees = $manager->employees->pluck('id')->toArray();
        $records = Record::whereIn('employee_id', $employees);

        return response()->json($records->paginate(10));
    }

    public function getByEmployeeRecordsByCategory(Request $request)
    {
        if (!Auth::guard('employee')->check()){
            throw new \Exception('Employee not logged');
        }

        if (Auth::guard('employee')->user()->cannot('getRecords', Employee::class)) {
            abort(403);
        }

        $validate = Validator::make($request->all(),
            [
                'category_id' => 'required',
            ]);

        $category = Category::find($request->get('category_id'));

        $records = $category->records->toArray();

        $filteredRecords = [];

        foreach ($records as $record) {
            if (Auth::guard('employee')->id() === $record['employee_id'])
            {
                $filteredRecords[] = $record;
            }
        }

        return response()->json($filteredRecords);
    }

    public function getByManagerRecordsByCategory(Request $request)
    {
        if (!Auth::guard('api')->check()){
            throw new \Exception('User not logged');
        }

        if (Auth::guard('api')->user()->cannot('getRecords', User::class)) {
            abort(403);
        }

        $validate = Validator::make($request->all(),
            [
                'category_id' => 'required',
            ]);

        $category = Category::find($request->get('category_id'));

        $records = $category->records->toArray();

        $filteredRecords = [];

        foreach ($records as $record) {
            if (Auth::guard('api')->id() === $record['employee_id'])
            {
                $filteredRecords[] = $record;
            }
        }

        return response()->json($filteredRecords);
    }
}
