<h3 class="text-center">Edit user information</h3>
<div class="container d-flex justify-content-center">
    <?php if (isset($data['confirmation'])) : ?>
        <span style="color:red;">
            <?= $data['message'] ?>
        </span>
    <?php endif; ?>
</div>
<div class="container d-flex justify-content-center mt-3">
    <form action="/edit-user" method="post" style="width: 30rem;">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="<?= $data['user']->email ?>"
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
                    value="<?= $data['user']->name ?>"
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
                    value="<?= $data['user']->surname ?>"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Input password to validate user</label>
            <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required
            >
        </div>
        <div class="d-flex justify-content-between">
            <a href="/edit-password">
                <button type="button" class="btn btn-outline-danger">Change Password</button>
            </a>
            <button type="submit" class="btn btn-outline-secondary">Submit changes</button>
        </div>
    </form>
</div>
