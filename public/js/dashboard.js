// dashboard.js

document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('jwt');
    const url = window.location.href;
    const pageRegex = /page=(\d+)/;
    const match = url.match(pageRegex);
    const page = match ? match[1] : "1";



    const pageSize = 4;

    //console.log(page); // Output: 2
    if (!token) {
        window.location.href = "/login";
    }

    //console.log(token);
    let $uuid;
    try {
        const response = await fetch('/api/dashboard', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        if (response.status === 401) {
            // Unauthorized access, redirect to login page
            window.location.href = "/login";
            return;
        }

        const result = await response.json();
        //console.log("Hello");
        console.log(result)
        $uuid = result.data.data.uuid;
        //console.log($uuid);
        if (response.ok) {
            const adminPanel = document.querySelector(".admin-panel-btn");
            if(!result.data.data.isAdmin) {
                adminPanel.classList.add('hidden');
            }else{
                adminPanel.classList.remove('hidden');
            }
           // console.log(result);
            // Populate the dashboard with the user data
            document.querySelector('.page-name p').textContent = 'Dashboard, Hello ' + result.data.data.username;
            // Fill in other parts of the page using result.data
            //let user = JSON.parse(localStorage.getItem("user"));
            //console.log(user);
            //console.log(user.uuid);
            const userLink = document.querySelector(".view-profile-btn");
            //userLink.href = "/user/" + user.uuid;
            userLink.href = "/user/" + $uuid;

        } else {
            // Handle the error
            console.error(result.message);
        }

    } catch (error) {
        // Handle any errors
        console.error(error);
    }


    // fetch the projects
    try {
        //console.log($uuid);
        const apiURL = "/api/project/user/"+$uuid;
        //console.log(apiURL);
        const response = await fetch(apiURL, {
            method: 'GET',
            headers: {
                Authorization: 'Bearer ' + token,
                'Content-Type': 'application/json',
            }
        });
        const projectContainer = document.querySelector('.project-area');

        if(response.status === 404){
            console.log("Nu am proiecte");
            const alertCard = document.createElement('div');
            alertCard.classList.add('project');
            alertCard.textContent = "This user has no projects"
            projectContainer.appendChild(alertCard);
            const buttonPrevious = document.querySelector('.button-previous');
            const buttonNext = document.querySelector('.button-next');
            buttonPrevious.classList.add('hidden');
            buttonNext.classList.add('hidden');
        }

        //console.log(response);
        if (response.ok) {

            const result = await response.json();
            console.log(result);
            // Get the parent container where the project cards will be appended

            const countProjects = result.data.projects.length;


            try{
                const apiURL2 = "/api/project/user/"+$uuid+"/"+page;
                //console.log(apiURL2)
                const actualProjects = await fetch(apiURL2, {
                    method: 'GET',
                    headers: {
                        Authorization: 'Bearer ' + token,
                        'Content-Type': 'application/json',
                    }
                });
                if(actualProjects.ok){
                    const resultActualProjects = await actualProjects.json();
                    //console.log(resultActualProjects);
                    resultActualProjects.data.projects.forEach(project => {
                        // Create a new project card
                        const projectCard = document.createElement('div');
                        projectCard.classList.add('project');
                        projectCard.classList.add(`project-${project.uuid}`); // Assign a unique class for this project

                        // Create the project name element
                        const projectName = document.createElement('p');
                        projectName.classList.add('project-name');
                        projectName.textContent = project.name;

                        // Create the button area
                        const buttonArea = document.createElement('div');
                        buttonArea.classList.add('button-area');

                        // Create the view button
                        const viewButton = document.createElement('a');
                        viewButton.classList.add('button');
                        //viewButton.href = `/project/${project.uuid}`;
                        viewButton.textContent = 'View';

                        viewButton.addEventListener('click', async (event) => {
                            event.preventDefault();
                            console.log('View Button clicked');
                            console.log('UUID:${user.uuid}');


                            try {
                                const apiUrl = `/api/project/${project.uuid}`;
                                const responseGet = await fetch(apiUrl, {
                                    method: 'GET',
                                    headers: {
                                        Authorization: 'Bearer ' + token,
                                        'Content-Type': 'application/json',
                                    },
                                });

                                if (responseGet.ok) {
                                    // The view was successful
                                    const URL = `/project/${project.uuid}`;
                                    window.location.href = URL;
                                } else {
                                    // Handle the error
                                    console.error(result.message);
                                }
                            } catch (error) {
                                // Handle any errors
                                console.error(error);
                            }

                        });

                        // Create the delete button
                        const deleteButton = document.createElement('a');
                        deleteButton.classList.add('button');
                        //deleteButton.href = `/project/${project.uuid}/delete`;
                        deleteButton.textContent = 'Delete';

                        deleteButton.addEventListener('click', async (event) => {
                            event.preventDefault();
                            //console.log('Delete Button clicked');
                            //console.log('UUID:${user.uuid}');

                            try {
                                const apiUrl = `/api/project/${project.uuid}`;
                                const responseDELETE = await fetch(apiUrl, {
                                    method: "DELETE",
                                    headers: {
                                        Authorization: 'Bearer ' + token,
                                        'Content-Type': 'application/json',
                                    },
                                    body: project.uuid,
                                });

                                if (responseDELETE.ok) {
                                    window.location.href = "/dashboard";
                                } else {
                                    // Handle the error
                                    console.error(result.message);
                                }
                            } catch (error) {
                                // Handle any errors
                                console.error(error);
                            }

                        });

                        // Append the project name and buttons to the project card
                        projectCard.appendChild(projectName);
                        buttonArea.appendChild(viewButton);
                        buttonArea.appendChild(deleteButton);
                        projectCard.appendChild(buttonArea);

                        // Append the project card to the parent container
                        projectContainer.appendChild(projectCard);
                    });
                    //console.log(pageSize);
                    //console.log(result.data.projects.length);
                    const buttonPrevious = document.querySelector('.button-previous');
                    const buttonNext = document.querySelector('.button-next');

                    if (page === "1") {
                        buttonPrevious.classList.add('hidden');
                    } else {
                        buttonPrevious.classList.remove('hidden');
                    }

                    if (resultActualProjects.data.projects.length < pageSize || countProjects <= pageSize*parseInt(page)) {
                        buttonNext.classList.add('hidden');
                    } else {
                        buttonNext.classList.remove('hidden');
                    }

                    buttonPrevious.addEventListener('click', async (event) => {
                        event.preventDefault();
                        window.location.href = `/dashboard?page=${parseInt(page) - 1}`;

                    });

                    buttonNext.addEventListener('click', async (event) => {
                        event.preventDefault();
                        //console.log("Next button clicked");
                        window.location.href = `/dashboard?page=${parseInt(page) + 1}`;
                    });

                } else {
                    // Handle the error
                    console.error(result.message);
                }
            }catch(error){

            }

        } else {
            // Handle the error
            console.error(result.message);
        }
    } catch (error) {

    }

});