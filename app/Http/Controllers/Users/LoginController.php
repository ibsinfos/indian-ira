<?php

namespace IndianIra\Http\Controllers\Users;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;
use IndianIra\Utilities\ProcessLoginCredentials;

class LoginController extends Controller
{
    use ProcessLoginCredentials;

    /**
     * Display the login form.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('users.dashboard'));
        }

        if (request()->exists('addToWishlist') && request()->addToWishlist != null) {
            session(['addToWishlist' => request()->addToWishlist]);
        }

        return view('users.login');
    }

    /**
     * Login the user after validation.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function postLogin(Request $request)
    {
        if (auth()->check()) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'You are already logged in...',
                'location' => route('users.dashboard'),
            ]);
        }

        $this->validate($request, [
            'usernameOrEmail' => 'required',
            'password'        => 'required'
        ]);

        $loggedIn = $this->processCredentials($request);

        if ($loggedIn) {
            $location = $this->processWishlistRequest();

            return response([
                'status'   => 'success',
                'title'    => 'Success !',
                'message'  => 'Logged in successfully. Redirecting...',
                'location' => $location
            ]);
        }

        return response([
            'status'  => 'failed',
            'title'   => 'Failed !',
            'delay'   => 3000,
            'message' => 'Invalid Credentials'
        ]);
    }

    /**
     * Process the wishlist request.
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    protected function processWishlistRequest()
    {
        $user = auth()->user();

        if (session('addToWishlist') != null) {
            $productInWishlist = $user->wishlist->where('product_code', session('addToWishlist'))->first();

            if ($productInWishlist) {
                session()->forget('addToWishlist');

                return route('users.wishlist');
            }

            $product = \IndianIra\Product::onlyEnabled()->whereCode(session('addToWishlist'))->first();

            $user->wishlist()->create([
                'product_id'       => $product->id,
                'product_name'     => $product->name,
                'product_code'     => $product->code,
                'product_image'    => $product->cartImage(),
                'product_page_url' => $product->canonicalPageUrl(),
            ]);

            session()->forget('addToWishlist');

            return route('users.wishlist');
        }

        return route('users.dashboard');
    }
}
