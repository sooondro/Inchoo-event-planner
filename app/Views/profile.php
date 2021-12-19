
<div class="container d-flex justify-content-center mt-3">
    <div class="card" style="width: 35rem;">
        <div class="card-body">
            <h5 class="card-title">User information</h5>

            <div class="container mb-3" style="border: 1px solid gray; padding: 10px">
                <div class="row">
                    <div class="col">
                        Name:
                    </div>
                    <div class="col">
                        <?= $data['user']->name ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Surname:
                    </div>
                    <div class="col">
                        <?= $data['user']->surname ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Email:
                    </div>
                    <div class="col">
                        <?= $data['user']->email ?>
                    </div>
                </div>
            </div>
            <a href="/edit-user" class="btn btn-primary">Edit user information</a>
        </div>
    </div>
</div>
