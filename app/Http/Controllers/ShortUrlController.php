<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\ShortUrlPermission;
use App\Models\CompanyUserMapping;
use App\Models\ShortUrl;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShortUrlController extends Controller
{
    // View All Users
    public function viewAllShortUrl(): mixed {

        $user = User::find(Auth::id());

        if ($user->hasPermissionTo(ShortUrlPermission::VIEW_ALL_SHORT_URLS->value)) {
            $short_urls = ShortUrl::all();
            
            return view('pages.short-url.list', [
                'short_urls' => $short_urls,
            ]);
        }

        if ($user->hasPermissionTo(ShortUrlPermission::VIEW_COMPANY_SHORT_URL->value)) {

            $records = CompanyUserMapping::where('user_id', $user->id)
                ->with(['company'])
                ->get();

            $companies = $records->pluck('company')->unique('id')->values();

            $short_urls = ShortUrl::whereIn('company_id', function ($query) use ($user) {
                $query->select('company_id')
                    ->from('company_user_mappings')
                    ->where('user_id', $user->id);
            })
                ->with(['user'])
                ->get();

            return view('pages.short-url.list', [
                'short_urls' => $short_urls,
                'companies' => $companies,
            ]);
        }

        if ($user->hasPermissionTo(ShortUrlPermission::VIEW_SELF_SHORT_URL->value)) {

            $records = CompanyUserMapping::where('user_id', $user->id)
                ->with(['company'])
                ->get();
            
            $companies = $records->pluck('company')->unique('id')->values();

            $short_urls = ShortUrl::where('user_id',$user->id)->get();
            
            return view('pages.short-url.list', [
                'short_urls' => $short_urls,
                'companies' => $companies,
            ]);
        }
        return abort(403, 'Unauthorized action.');
    }

    // Handle Create Invite
    public function handleCreateShortUrl(Request $request): RedirectResponse {

        try {

            $validator = Validator::make($request->all(),[
                'original_url' => ['required', 'string'],
                'company_id' => ['required', 'string', 'exists:companies,id'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('message', [
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ])->withInput();
            }

            $short_url = new ShortUrl();
            $short_url->user_id = Auth::id();
            $short_url->company_id = $request->input('company_id');
            $short_url->original_url = $request->input('original_url');
            $short_url->generateShortUrlCode();
            $short_url->save();

            
            return redirect()->route('view.all.shorturl')->with('message', [
                'status' => 'success',
                'message' => 'Short URL : ' . route('check.short-url',['short_url_code' => $short_url->short_url_code]) 
            ]);

        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }

    // View Short URL
    public function checkShortUrl(string $short_url_code): mixed {

        $short_url = ShortUrl::where('short_url_code', $short_url_code)->first();

        if (!$short_url) {
            return abort(404);
        }

        $short_url->hit_count = $short_url->hit_count + 1;
        $short_url->save();

        return redirect($short_url->original_url);
    }
}
