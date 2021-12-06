<!-- <div class="container d-flex justify-content-center">
    <?php if ($data && $data['confirmation'] === 'success') : ?>
        <span style="color:green;">
            <?= $data['message'] ?>
        </span>
    <?php elseif ($data) : ?>
        <span style="color:red;">
            <?= $data['message'] ?>
        </span>
    <?php endif; ?>
</div> -->
<h3 class="text-center">Login to your account</h3>
<div class="container d-flex justify-content-center mt-3">
    <form action="login" method="post" style="width: 30rem;">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input
                    type="email"
                    class="form-control"
                    id="email" name="email"
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
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
