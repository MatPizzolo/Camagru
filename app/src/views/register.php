<!-- /app/views/register.php -->

<?php include 'components/header.php'; ?>
    
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form action="index.php?action=register" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="loginEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" value="Register" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
            <div class="mt-5 text-center">
                <p>Already have an account? <a href="/login">Log in here</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>