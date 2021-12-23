<h3 class="text-center">All users</h3>
<div class="container">
    <?php foreach ($data['users'] as $user): ?>
        <div class="mt-3 pb-3" style="border-bottom: 1px solid lightgray">
            <form action="/admin-panel" method="post" class="row row-cols-lg-auto g-3 align-items-center">
                <div class="col-lg-4 mb-2 mb-lg-0">
                    User - <?= $user->name . ' ' . $user->surname ?>
                </div>
                <div class="col-lg-4 mb-2 mb-lg-0">
                    Email - <?= $user->email ?>
                </div>
                <input hidden name="id" value="<?= $user->id ?>">
                <?php if (!$user->admin): ?>
                    <div class="col-lg-4 d-flex justify-content-around">
                        <button type="submit" class="btn btn-outline-secondary">Make Admin</button>
                        <a href="/delete-user?userId=<?= $user->id ?>">
                            <button type="button" class="btn btn-outline-danger">Delete User</button>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="col-lg-4 text-center">
                        <button type="submit" class="btn btn-secondary" disabled>Admin</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    <?php endforeach; ?>
</div>