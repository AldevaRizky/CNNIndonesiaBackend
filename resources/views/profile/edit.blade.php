@extends('layouts.dashboard')
@section('title', 'My Profile')
@section('content')

@php $user = $user ?? auth()->user(); @endphp

<div class="container mt-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>My Profile</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <script>Swal.fire('Sukses', "{{ session('success') }}", 'success');</script>
            @endif

            @php
                // Build an errors array but exclude password/current_password unless a password change was attempted
                $showPasswordErrors = old('password');
                $allMessages = [];
                foreach ($errors->messages() as $key => $msgs) {
                    if (in_array($key, ['password', 'current_password']) && !$showPasswordErrors) {
                        continue;
                    }
                    foreach ($msgs as $m) $allMessages[] = $m;
                }
            @endphp

            @if (!empty($allMessages))
                <script>
                    (function(){
                        var errs = {!! json_encode($allMessages) !!};
                        var html = '<ul style="text-align:left;margin:0;padding-left:1em">';
                        errs.forEach(function(e){ html += '<li>'+e+'</li>'; });
                        html += '</ul>';
                        Swal.fire({icon:'error', title:'Terjadi kesalahan', html: html});
                    })();
                </script>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <input type="hidden" name="current_password" id="current_password_hidden" />

                <div class="mb-4 d-flex align-items-start gap-4">
                    <img id="uploadedAvatar" src="{{ $user->profile_url ?? asset('assets/img/CNN_International_logo.png') }}" alt="avatar" class="rounded" style="width:100px;height:100px;object-fit:cover;" />
                    <div>
                        <label class="btn btn-primary mb-2" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <input type="file" id="upload" name="profile" class="account-file-input" hidden accept="image/png, image/jpeg" />
                        </label>
                        <button type="button" id="resetAvatar" class="btn btn-outline-secondary mb-2">Reset</button>
                        <div class="text-muted">Allowed JPG, GIF or PNG. Max size of 10MB</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $user->jabatan) }}" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" />
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $user->alamat) }}" />
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $user->website) }}" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Twitter (url)</label>
                        <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $user->twitter) }}" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Instagram (url)</label>
                        <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $user->instagram) }}" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Facebook (url)</label>
                        <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $user->facebook) }}" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="{{ old('dob', optional($user->dob)->format('Y-m-d')) }}" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">-</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $user->country) }}" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control" value="{{ old('state', $user->state) }}" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">ZIP</label>
                        <input type="text" name="zip" class="form-control" value="{{ old('zip', $user->zip) }}" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" name="role" class="form-control" value="{{ old('role', $user->role) }}" />
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Address line</label>
                        <input type="text" name="address_line" class="form-control" value="{{ old('address_line', $user->address_line) }}" />
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" id="saveProfileBtn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <h5 class="card-header">Delete Account</h5>
        <div class="card-body">
            <div class="alert alert-warning">
                <h5 class="alert-heading">Are you sure you want to delete your account?</h5>
                <p>Once you delete your account, there is no going back. Please be certain.</p>
            </div>

            <form action="{{ route('profile.destroy') }}" method="POST" id="deleteAccountForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="password" id="delete_current_password_hidden" />
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                    <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                </div>
                <button type="button" id="deactivateBtn" class="btn btn-danger">Deactivate Account</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // preview avatar
    document.getElementById('upload')?.addEventListener('change', function(e){
        const file = e.target.files && e.target.files[0];
        if (!file) return;
        const img = document.getElementById('uploadedAvatar');
        img.src = URL.createObjectURL(file);
    });

    // reset avatar preview (does not remove existing on server)
    document.getElementById('resetAvatar')?.addEventListener('click', function(){
        const img = document.getElementById('uploadedAvatar');
        img.src = '{{ $user->profile_url ?? asset('assets/img/CNN_International_logo.png') }}';
        const input = document.getElementById('upload');
        if (input) input.value = null;
    });

    // Save profile handler (with confirmation). If password fields filled, prompt for current password first.
    document.getElementById('saveProfileBtn')?.addEventListener('click', function(){
        const form = this.closest('form');
        const pwd = form.querySelector('input[name=password]')?.value || '';

        function finalSave() {
            Swal.fire({
                title: 'Simpan perubahan?',
                text: 'Perubahan pada profil akan disimpan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        }

        if (!pwd) {
            finalSave();
            return;
        }

        // If changing password, ask for current password via SweetAlert input
        Swal.fire({
            title: 'Masukkan kata sandi saat ini',
            input: 'password',
            inputPlaceholder: 'Kata sandi saat ini',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Masukkan kata sandi saat ini');
                }
                return value;
            }
        }).then(r => {
            if (r.isConfirmed) {
                document.getElementById('current_password_hidden').value = r.value;
                finalSave();
            }
        });
    });

    // delete account handler with current-password prompt and confirmation
    document.getElementById('deactivateBtn')?.addEventListener('click', function(){
        const form = document.getElementById('deleteAccountForm');
        const checkbox = document.getElementById('accountActivation');
        if (!checkbox.checked) {
            Swal.fire('Perhatian', 'Centang konfirmasi penghapusan akun terlebih dahulu.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Masukkan kata sandi saat ini',
            input: 'password',
            inputPlaceholder: 'Kata sandi saat ini',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            preConfirm: (value) => {
                if (!value) Swal.showValidationMessage('Masukkan kata sandi saat ini');
                return value;
            }
        }).then(r => {
            if (!r.isConfirmed) return;
            document.getElementById('delete_current_password_hidden').value = r.value;

            Swal.fire({
                title: 'Hapus akun?',
                text: 'Akun Anda akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((res) => {
                if (res.isConfirmed) form.submit();
            });
        });
    });

    // Show SweetAlert for specific server-side password errors only when a password change was submitted
    @if (old('password') && ($errors->has('current_password') || $errors->has('password')))
        (function(){
            @if ($errors->has('current_password'))
                var title = 'Masukkan kata sandi saat ini';
                var msg = {!! json_encode($errors->first('current_password')) !!};
            @elseif ($errors->has('password'))
                var raw = {!! json_encode($errors->first('password')) !!};
                var lower = raw.toLowerCase();
                var isConfirmation = lower.includes('confirmation') || lower.includes('does not match') || lower.includes('tidak cocok') || lower.includes('konfirmasi') || lower.includes('tidak sama');
                var title = isConfirmation ? 'Konfirmasi password salah' : 'Password tidak valid';
                var msg = raw;
            @endif

            Swal.fire({
                icon: 'error',
                title: title,
                text: msg
            });
        })();
    @endif
</script>
@endpush

@endsection
