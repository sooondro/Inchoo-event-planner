<div class="container">
    <?php foreach ($data['users'] as $user): ?>
        <form action="/admin-panel" method="post" class="row row-cols-lg-auto g-3 align-items-center mb-3">
            <div class="col">
                User - <?= $user->name . ' ' . $user->surname ?>
            </div>
            <div class="col">
                Email - <?= $user->email ?>
            </div>
            <input hidden name="id" value="<?= $user->id ?>">
            <?php if (!$user->admin): ?>
                <div class="col text-center">
                    <button type="submit" class="btn btn-outline-secondary">Make Admin</button>
                </div>
            <?php else: ?>
                <div class="col text-center">
                    <button type="submit" class="btn btn-secondary" disabled>Admin</button>
                </div>
            <?php endif; ?>
        </form>
    <?php endforeach; ?>
</div>