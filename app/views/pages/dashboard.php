<?php require_once '../app/views/shared/navbar.php'; ?>
<main class = "central-area">
    <div class="page-name">
        <p>Dashboard</p>
    </div>
    <div>
        <a href="/profile" class="view-profile-btn">View Profile</a>
        <a href="/project/create" class="create-project-btn">Create new project</a>
    </div>
            
    <div class="project-area">
                
        <div class = "project project-1">
            <p>Project Name 1</p>
            <div class ="button-area">
                <a href="/project/1" class="button">View</a>
                <a href="/project/1/edit" class="button">Modify</a>
                <a href="/project/1/delete" class="button">Delete</a>
            </div>
        </div>

        <div class = "project project-2">
            <p>Project Name 2</p>
            <div class ="button-area">
                <a href="/project/2" class="button">View</a>
                <a href="/project/2/edit" class="button">Modify</a>
                <a href="/project/2/delete" class="button">Delete</a>
            </div>
        </div>
            
        <div class = "project project-3">
            <p>Project Name 3</p>
            <div class ="button-area">
                <a href="/project/3" class="button">View</a>
                <a href="/project/3/edit" class="button">Modify</a>
                <a href="/project/3/delete" class="button">Delete</a>
            </div>
        </div>

        <div class = "project project-4">
            <p>Project Name 4</p>
            <div class ="button-area">
                <a href="/project/4" class="button">View</a>
                <a href="/project/4/edit" class="button">Modify</a>
                <a href="/project/4/delete" class="button">Delete</a>
            </div>
        </div>
                
    </div>
           

</main>
<?php require_once '../app/views/shared/footer.php'; ?>


