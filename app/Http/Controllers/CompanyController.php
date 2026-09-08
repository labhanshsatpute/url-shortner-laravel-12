<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\CompanyPermission;
use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CompanyController extends Controller
{
    // View All Companies
    public function viewAllCompanies(): mixed {
        if (User::find(Auth::id())->hasPermissionTo(CompanyPermission::COMPANY_VIEW->value)) {
            $companies = Company::all();
            return view('pages.company.list',[
                'companies' => $companies
            ]);
        }
        return abort(403, 'Unauthorized action.');
    }

    // Handle Create Company Request
    public function handleCreateCompany(Request $request): RedirectResponse {

        try {

            $validator = Validator::make($request->all(),[
                'name' => ['required', 'string', 'min:2', 'max:250', 'unique:companies']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $company = new Company();
            $company->name = $request->input('name');
            $company->save();

            return redirect()->route('view.all.companies')->with('message', [
                'status' => 'success',
                'message' => 'Company created successfully'
            ]);

        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
}
