<?php require_once '../app/views/shared/navbar.php'; ?>
<main class = "central-area">
    <div class="page-name">
        <p>List of projects</p>
    </div>
    <div>
        <a href="/project/create" class="create-project-btn">Create new project</a>
    </div>
            
    <div class="project-area">
                
        <div class = "project project-1">
            <p>Project Name</p>
            <div class ="button-area">
                <a href="/project/1" class="button">View</a>
                <a href="/project/1/edit" class="button">Modify</a>
                <a href="/project/1/delete" class="button">Delete</a>
            </div>
        </div>

        <div class = "project project-2">
            <p>Project Name</p>
            <div class ="button-area">
                <a href="/project/2" class="button">View</a>
                <a href="/project/2/edit" class="button">Modify</a>
                <a href="/project/2/delete" class="button">Delete</a>
            </div>
        </div>
            
        <div class = "project project-3">
            <p>Project Name</p>
            <div class ="button-area">
                <a href="/project/3" class="button">View</a>
                <a href="/project/3/edit" class="button">Modify</a>
                <a href="/project/3/delete" class="button">Delete</a>
            </div>
        </div>

        <div class = "project project-4">
            <p>Project Name</p>
            <div class ="button-area">
                <a href="/project/4" class="button">View</a>
                <a href="/project/4/edit" class="button">Modify</a>
                <a href="/project/4/delete" class="button">Delete</a>
            </div>
        </div>
                
    </div>
           

</main>
<?php require_once '../app/views/shared/footer.php'; ?>


