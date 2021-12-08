<h3 class="text-center">Create a new account!</h3>
<div class="container d-flex justify-content-center">
    <?php if (isset($data['confirmation'])) : ?>
        <span style="color:red;">
            <?= $data['message'] ?>
        </span>
    <?php endif; ?>
</div>
<div class="container d-flex justify-content-center mt-3">
    <form action="signup" method="post" style="width: 30rem;">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input
                    type="email"
                    class="form-control"
                    id="email" name="email"
                    value="<?= $data['formValues']['email'] ?? '' ?>"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?= $data['formValues']['name'] ?? '' ?>"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input
                    type="text"
                    class="form-control"
                    id="surname"
                    name="surname"
                    value="<?= $data['formValues']['surname'] ?? ''?>"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    minlength="6"
                    required>
        </div>
        <div class="mb-3">
            <label for="repeated-password" class="form-label">Repeated Password</label>
            <input
                    type="password"
                    class="form-control"
                    id="repeated-password"
                    name="repeated-password"
                    minlength="6"
                    required>
        </div>
        <!-- <div class="mb-3 form-check">
             <input type="checkbox" class="form-check-input" id="admin" name="admin" value="true">
             <label class="form-check-label" for="admin">Admin</label>
         </div> -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
