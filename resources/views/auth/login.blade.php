@extends('layouts.app', ['title' => 'Login'])

@section('content')
<div class="w-full max-w-5xl mx-auto bg-card rounded-3xl card-shadow border border-border flex flex-col md:flex-row overflow-hidden min-h-[520px]">
    
    <!-- Left Side: Lottie Animation -->
    <div class="hidden md:flex md:w-1/2 bg-primary/5 flex-col items-center justify-center p-8 lg:p-12 relative">
        <!-- Optional decorative elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-secondary/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-sm">
            <lottie-player 
                src="{{ asset('d788a264-1188-11ee-870a-d761e719f38a.json') }}" 
                background="transparent" 
                speed="1" 
                style="width: 100%; height: auto;" 
                loop 
                autoplay>
            </lottie-player>
            <div class="text-center mt-6">
                <h2 class="text-xl font-bold text-primary mb-2">Manajemen Stok Masjid</h2>
                <p class="text-sm text-gray-500">Kelola inventaris dan distribusi kebutuhan masjid dengan lebih mudah, cepat, dan transparan.</p>
            </div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 p-8 lg:p-12" x-data="loginForm()">
        <div class="text-center mb-8">
            @if(isset($appSetting) && $appSetting->logo)
                <img src="{{ Storage::url($appSetting->logo) }}" alt="Logo" class="h-16 mx-auto mb-4 object-contain">
            @else
                <div class="w-16 h-16 rounded-2xl bg-primary text-white flex items-center justify-center font-bold text-3xl shadow-sm mx-auto mb-4">
                    {{ isset($appSetting) && $appSetting->nama_sistem ? substr($appSetting->nama_sistem, 0, 1) : 'S' }}
                </div>
            @endif
            <h1 class="text-2xl font-bold text-primary mb-2">{{ $appSetting->nama_sistem ?? 'Sistem Informasi Mesjid' }}</h1>
            <p class="text-gray-500 text-sm">{{ $appSetting->nama_yayasan ?? 'Yayasan Haji Anif' }}</p>
        </div>

        <form @submit.prevent="submitForm">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-text mb-1" for="email">Email</label>
                    <input 
                        x-model="formData.email"
                        type="email" 
                        id="email" 
                        class="w-full px-4 py-3 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-smooth text-sm"
                        placeholder="Masukkan email Anda"
                        required
                    >
                    <p x-show="errors.email" class="text-danger text-xs mt-1" x-text="errors.email"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1" for="password">Password</label>
                    <input 
                        x-model="formData.password"
                        type="password" 
                        id="password" 
                        class="w-full px-4 py-3 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-smooth text-sm"
                        placeholder="Masukkan password Anda"
                        required
                    >
                    <div class="flex justify-between items-center mt-2">
                        <p x-show="errors.password" class="text-danger text-xs" x-text="errors.password"></p>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-primary hover:text-teal-800 transition-smooth ml-auto">Lupa Password?</a>
                    </div>
                </div>

                <div x-show="errors.general" class="p-3 bg-danger/10 text-danger rounded-xl text-sm" x-text="errors.general" style="display: none;"></div>

                <button 
                    type="submit" 
                    class="w-full py-3 px-4 bg-primary hover:bg-teal-800 text-white rounded-xl font-medium transition-smooth focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary flex items-center justify-center"
                    :disabled="loading"
                    :class="{'opacity-75 cursor-not-allowed': loading}"
                >
                    <span x-show="!loading">Masuk</span>
                    <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function loginForm() {
        return {
            formData: {
                email: '',
                password: '',
                _token: '{{ csrf_token() }}'
            },
            errors: {},
            loading: false,
            submitForm() {
                this.loading = true;
                this.errors = {};

                fetch('{{ route("login.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.formData._token
                    },
                    body: JSON.stringify(this.formData)
                })
                .then(response => response.json().then(data => ({status: response.status, body: data})))
                .then(res => {
                    this.loading = false;
                    
                    if (res.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Berhasil',
                            text: 'Mengarahkan ke dashboard...',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = res.body.redirect;
                        });
                    } else if (res.status === 422) {
                        // Validation errors
                        for(let key in res.body.errors) {
                            this.errors[key] = res.body.errors[key][0];
                        }
                    } else {
                        // Other errors (401)
                        this.errors.general = res.body.message || 'Terjadi kesalahan sistem.';
                    }
                })
                .catch(error => {
                    this.loading = false;
                    this.errors.general = 'Terjadi kesalahan jaringan.';
                    console.error('Error:', error);
                });
            }
        }
    }
</script>
@endpush
@endsection
