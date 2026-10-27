<x-guest-layout>
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="paio-form-group">
            <x-input-label
                for="email"
                :value="__('Email Address')"
                class="paio-label"
            />

            <x-text-input
                id="email"
                class="paio-input block mt-2 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="Enter your email address"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        <!-- Password -->
        <div class="paio-form-group mt-6">
            <x-input-label
                for="password"
                :value="__('Password')"
                class="paio-label"
            />

            <x-text-input
                id="password"
                class="paio-input block mt-2 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>

        <!-- Remember Me -->
        <div class="paio-remember mt-5">
            <label for="remember_me">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                >
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Forgot Password -->
        @if (Route::has('password.request'))
            <div class="paio-forgot-wrapper">
                <a
                    href="{{ route('password.request') }}"
                    class="paio-forgot"
                >
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif

        <!-- Sign In -->
        <button type="submit" class="paio-login-button">
            <span aria-hidden="true">↪</span>
            {{ __('Sign In') }}
        </button>

        <div class="paio-security-note">
            <span aria-hidden="true">♢</span>
            Authorized personnel only
        </div>
    </form>

    <style>
        .paio-label {
            display: block;
            color: #055b25 !important;
            font-size: 16px;
            font-weight: 600;
        }

        .paio-input {
            display: block;
            width: 100%;
            min-height: 54px;
            padding: 12px 16px;
            color: #263b2d !important;
            background: #fff !important;
            border: 1px solid #c8ddce !important;
            border-radius: 11px !important;
            box-shadow: none !important;
            font-size: 16px;
        }

        .paio-input:focus {
            border-color: #318d52 !important;
            outline: 2px solid #7baf94 !important;
            outline-offset: 2px;
        }

        .paio-input::placeholder {
            color: #929a94;
        }

        .paio-remember label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #538e6b;
            font-size: 15px;
            cursor: pointer;
        }

        .paio-remember input {
            width: 19px;
            height: 19px;
            accent-color: #055b25;
        }

        .paio-forgot-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }

        .paio-forgot {
            color: #538e6b;
            font-size: 15px;
            text-decoration: none;
        }

        .paio-forgot:hover {
            color: #055b25;
            text-decoration: underline;
        }

        .paio-login-button {
            width: 100%;
            min-height: 58px;
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            border: none;
            border-radius: 12px;
            background: #055b25;
            color: #fff;
            font-size: 19px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        .paio-login-button:hover {
            background: #34784e;
            transform: translateY(-1px);
        }

        .paio-login-button:focus-visible {
            outline: 3px solid #d2ae47;
            outline-offset: 3px;
        }

        .paio-security-note {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px solid #e0ece3;
            color: #538e6b;
            font-size: 15px;
        }

        @media (max-width: 600px) {
            .paio-input {
                min-height: 48px;
                font-size: 15px;
            }

            .paio-login-button {
                min-height: 50px;
                font-size: 17px;
            }
        }
        /* Improve spacing between login fields */
.paio-form-group + .paio-form-group {
    margin-top: 26px !important;
}

.paio-input {
    min-height: 52px;
}

.paio-remember {
    margin-top: 24px !important;
}

.paio-forgot-wrapper {
    margin-top: 18px;
}

.paio-login-button {
    margin-top: 28px;
}
    </style>
</x-guest-layout>