<?php
/**
 *
 * @author Michal Carson <michal.carson@carsonsoftwareengineering.com>
 *
 */

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{

    protected $rules = [
        'name' => 'string|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required_with:password_confirmation|string|min:6',
        'password_confirmation' => 'string|same:password',
        'hobbies' => 'string',
        'avatar' => 'image',
        'admin' => 'boolean'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! Auth::user()->admin) {
            return redirect('/user/home');
        }

        if ($request->wantsJson()) {
            $users = User::get();
            return $users;
        }
        return View('admin.home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $input = $request->except('_token');
        $user = User::create($input);
        return ['response' => 'success', 'id' => $user->id];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id)->toArray();
        return View('user')->with($user);
    }

    /**
     * Update the user's own profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws UnauthorizedException
     */
    public function updateSelf(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->id != $id) {
            throw new UnauthorizedException();
        }

        $rc = $this->update($request, $id);

        if ($rc['response'] == 'success') {

            return redirect('user/home');

        }

        return redirect('user/home')->withErrors();

    }

    /**
     * Save the uploaded avatar file to the a directory under the public root.
     *
     * @param Request $request
     * @param User $user
     */
    protected function saveAvatar(Request $request, User $user) {

        // save the avatar, no check for duplicates
        if ($request->hasFile('avatar')) {

            // clean up a little bit
            // @todo: remove the original file extension from $name
            $file = $request->file('avatar');
            $name = preg_replace('/[^0-9A-Za-z]/', '', $file->getClientOriginalName());
            $ext = $file->guessExtension();

            // copy the file to our avatar directory under the public root
            $file->move(public_path('avatars'), strtolower("$name.$ext"));

            // save the file name on the user
            $user->avatar = "/avatars/$name.$ext";
            $user->save();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->rules['name'] .= ",id,$id";
        $this->rules['email'] .= ",id,$id";
        $this->validate($request, $this->rules);

        // don't blank out the password!
        if (!$request->has('password')) {
            $request->request->remove('password');
        }

        // if admin checkbox is unchecked, it will not be present in the request
        if (!$request->has('admin')) {
            $request->request->set('admin', false);
        }

        $input = $request->except('_token');
        $user = User::updateOrCreate(['id' => $id], $input);

        // must do something with the file
        $this->saveAvatar($request, $user);

        if (Auth::user()->admin) {
            return back();
        }
        return ['response' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::destroy([$id])) {
            return ['response' => 'success'];
        }
        abort();
    }

    /**
     * Return the validation rules
     * @return array
     */
    public function getRules() {
        return $this->rules;
    }
}
