document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('jwt');
    const url = window.location.href;
    const uuidRegex = /\/viewProjects\/([^\/?]+)/;
    const pageRegex = /page=(\d+)/;
    const uuidMatch = url.match(uuidRegex);
    const pageMatch = url.match(pageRegex);
    const uuid = uuidMatch ? uuidMatch[1] : null;
    const page = pageMatch ? parseInt(pageMatch[1]) : null;
    const pageSize = 4;

    //console.log(uuid);
    //console.log(page);
    // Show an error message if there is no uuid parameter in the URL
    if (!uuid) {
        alert("No UUID specified");
        return;
    }
    if (!token) {
        window.location.href = "/login";
    }
    //let $uuid;
    try {
        const firstApiURL = "/api/user/" + uuid;
        const response1 = await fetch(firstApiURL, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        if (response1.status === 401) {
            // Unauthorized access, redirect to login page
            window.location.href = "/login";
            return;
        }
        const result = await response1.json();
        //$uuid =result.data.data.uuid;
        //console.log(result)

        if (response1.ok) {
            console.log(result);
            // Populate the dashboard with the user data
            document.querySelector('.page-name p').textContent = 'Projects of user: ' + result.data.data.username;
            // Fill in other parts of the page using result.data
            //let user = JSON.parse(localStorage.getItem("user"));
            //console.log(user);
            //console.log(user.uuid);
            const userLink = document.querySelector(".view-profile-btn");
            //userLink.href = "/user/" + user.uuid;
            userLink.href = "/user/" + uuid;

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
        const apiURL = "/api/project/user/" + uuid;
        const response = await fetch(apiURL, {
            method: 'GET',
            headers: {
                Authorization: 'Bearer ' + token,
                'Content-Type': 'application/json',
            }
        });

        //console.log(uuid);
        console.log(response);

        const projectContainer = document.querySelector('.project-area');
        if (response.status === 404) {

            const alertCard = document.createElement('div');
            alertCard.classList.add('project');
            alertCard.textContent = "This user has no projects"
            projectContainer.appendChild(alertCard);

            const buttonPrevious = document.querySelector('.button-previous');
            const buttonNext = document.querySelector('.button-next');
            buttonPrevious.classList.add('hidden');
            buttonNext.classList.add('hidden');


        }

        if (response.ok) {

            const result = await response.json();
            console.log(result);
            const countProjects = result.data.projects.length;


            try {
                const apiURL2 = "/api/project/user/" + uuid + "/" + page;
                //console.log("Hello");
                //console.log(apiURL2)
                const actualProjects = await fetch(apiURL2, {
                    method: 'GET',
                    headers: {
                        Authorization: 'Bearer ' + token,
                        'Content-Type': 'application/json',
                    }
                });
                if (actualProjects.ok) {
                    const resultActualProjects = await actualProjects.json();
                    //console.log(resultActualProjects);
                    resultActualProjects.data.projects.forEach(project => {
                        const projectCard = document.createElement('div');
                        projectCard.classList.add('project');
                        projectCard.classList.add(`project-${project.uuid}`); // Assign a unique class for this project
                        const projectName = document.createElement('p');
                        projectName.classList.add('project-name');
                        projectName.textContent = project.name;
                        const buttonArea = document.createElement('div');
                        buttonArea.classList.add('button-area');
                        const viewButton = document.createElement('a');
                        viewButton.classList.add('button');
                        viewButton.textContent = 'View';
                        viewButton.addEventListener('click', async (event) => {
                            event.preventDefault();

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
                        deleteButton.textContent = 'Delete';
                        deleteButton.addEventListener('click', async (event) => {
                            event.preventDefault();
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
                        projectContainer.appendChild(projectCard);
                    });
                    const buttonPrevious = document.querySelector('.button-previous');
                    const buttonNext = document.querySelector('.button-next');
                    if (page === 1) {
                        buttonPrevious.classList.add('hidden');
                    } else {
                        buttonPrevious.classList.remove('hidden');
                    }

                    if (resultActualProjects.data.projects.length < pageSize || countProjects <= pageSize * page) {
                        buttonNext.classList.add('hidden');
                    } else {
                        buttonNext.classList.remove('hidden');
                    }

                    buttonPrevious.addEventListener('click', async (event) => {
                        event.preventDefault();
                        window.location.href = `/viewProjects/${uuid}?page=${parseInt(page) - 1}`;

                    });
                    buttonNext.addEventListener('click', async (event) => {
                        event.preventDefault();
                        window.location.href = `/viewProjects/${uuid}?page=${parseInt(page) + 1}`;
                    });

                } else {
                    // Handle the error
                    console.error(result.message);
                }
            } catch (error) {

            }

        } else {
            // Handle the error
            console.error(result.message);
        }
    } catch (error) {

    }

});