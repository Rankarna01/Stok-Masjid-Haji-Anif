@extends('layouts.app', ['title' => 'Verifikasi Kode OTP'])

@section('content')
<div class="w-full max-w-5xl mx-auto bg-card rounded-3xl card-shadow border border-border flex flex-col md:flex-row overflow-hidden min-h-[520px]">
    
    <!-- Left Side: Lottie Animation -->
    <div class="hidden md:flex md:w-1/2 bg-primary/5 flex-col items-center justify-center p-8 lg:p-12 relative">
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
                <h2 class="text-xl font-bold text-primary mb-2">Periksa Email Anda</h2>
                <p class="text-sm text-gray-500">Kami telah mengirimkan 6 digit angka rahasia. Kode tersebut hanya berlaku selama 15 menit demi keamanan akun Anda.</p>
            </div>
        </div>
    </div>

    <!-- Right Side: Verify OTP Form -->
    <div class="w-full md:w-1/2 p-8 lg:p-12" x-data="verifyOtpForm()">
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

        <div class="mb-6 text-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
            <h2 class="text-base font-bold text-textDark mb-1">Verifikasi Kode OTP</h2>
            <p class="text-xs text-gray-500 leading-relaxed">Masukkan 6 digit angka verifikasi yang dikirim ke email <strong class="text-textDark">{{ $email }}</strong></p>
        </div>

        <form @submit.prevent="submitForm">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 text-center" for="otp">Kode Verifikasi 6 Digit</label>
                    <input 
                        x-model="formData.otp"
                        type="text" 
                        id="otp" 
                        maxlength="6"
                        class="w-full px-4 py-4 rounded-2xl border border-border focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-smooth text-center font-extrabold text-2xl tracking-[0.5em] text-primary placeholder:font-normal placeholder:tracking-normal placeholder:text-sm placeholder:text-gray-400"
                        placeholder="123456"
                        required
                        autofocus
                    >
                    <p x-show="errors.otp" class="text-danger text-xs mt-2 text-center" x-text="errors.otp"></p>
                </div>

                <div x-show="errors.general" class="p-3 bg-danger/10 text-danger rounded-xl text-sm text-center" x-text="errors.general" style="display: none;"></div>
                <div x-show="successMessage" class="p-3 bg-success/10 text-success rounded-xl text-sm text-center font-medium" x-text="successMessage" style="display: none;"></div>

                <button 
                    type="submit" 
                    class="w-full py-3 px-4 bg-primary hover:bg-teal-800 text-white rounded-xl font-medium transition-smooth focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary flex items-center justify-center shadow-md hover:shadow-lg"
                    :disabled="loading"
                    :class="{'opacity-75 cursor-not-allowed': loading}"
                >
                    <span x-show="!loading">Verifikasi Kode</span>
                    <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>

                <!-- Resend OTP Section -->
                <div class="text-center pt-2">
                    <p class="text-xs text-gray-500 mb-2">Belum menerima kode di email Anda?</p>
                    <button 
                        type="button" 
                        @click="resendOtp"
                        class="text-xs font-bold text-primary hover:underline focus:outline-none"
                        :disabled="resending"
                        :class="{'opacity-50 cursor-not-allowed': resending}"
                    >
                        <span x-show="!resending">Kirim Ulang Kode OTP</span>
                        <span x-show="resending">Mengirim ulang...</span>
                    </button>
                </div>

                <div class="text-center border-t border-gray-100 pt-4 mt-4">
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-gray-500 hover:text-primary transition-smooth inline-flex items-center gap-1">
                        ← Ganti alamat email
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function verifyOtpForm() {
        return {
            formData: {
                email: '{{ $email }}',
                otp: '',
                _token: '{{ csrf_token() }}'
            },
            errors: {},
            loading: false,
            resending: false,
            successMessage: '',
            submitForm() {
                this.loading = true;
                this.errors = {};
                this.successMessage = '';

                fetch('{{ route("password.otp.verify") }}', {
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
                            title: 'Verifikasi Berhasil!',
                            text: res.body.message,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = res.body.redirect;
                        });
                    } else if (res.status === 422) {
                        if (res.body.errors) {
                            for(let key in res.body.errors) {
                                this.errors[key] = res.body.errors[key][0];
                            }
                        } else {
                            this.errors.general = res.body.message;
                        }
                    } else {
                        this.errors.general = res.body.message || 'Terjadi kesalahan sistem.';
                    }
                })
                .catch(error => {
                    this.loading = false;
                    this.errors.general = 'Terjadi kesalahan jaringan.';
                    console.error('Error:', error);
                });
            },
            resendOtp() {
                this.resending = true;
                this.errors = {};
                this.successMessage = '';

                fetch('{{ route("password.otp.resend") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.formData._token
                    },
                    body: JSON.stringify({ email: this.formData.email })
                })
                .then(response => response.json().then(data => ({status: response.status, body: data})))
                .then(res => {
                    this.resending = false;
                    if (res.status === 200) {
                        this.successMessage = res.body.message;
                        Swal.fire({
                            icon: 'info',
                            title: 'Kode Dikirim Ulang!',
                            text: res.body.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        this.errors.general = res.body.message || 'Gagal mengirim ulang kode.';
                    }
                })
                .catch(error => {
                    this.resending = false;
                    this.errors.general = 'Terjadi kesalahan jaringan saat kirim ulang.';
                });
            }
        }
    }
</script>
@endpush
