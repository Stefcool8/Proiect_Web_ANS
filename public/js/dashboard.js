// dashboard.js

document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('jwt');
    if (!token) {
        window.location.href = "/login";
    }

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
        //console.log(result)

        if (response.ok) {
            console.log(result);
            // Populate the dashboard with the user data
            document.querySelector('.page-name p').textContent = 'Dashboard, Hello ' + result.data.data.username;
            // Fill in other parts of the page using result.data
            //let user = JSON.parse(localStorage.getItem("user"));
            //console.log(user);
            //console.log(user.uuid);
            const userLink = document.querySelector(".view-profile-btn");
            //userLink.href = "/user/" + user.uuid;
            userLink.href = "/user/" + result.data.data.uuid;

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
        const response = await fetch('/api/project', {
            method: 'GET',
            headers: {
                Authorization: 'Bearer ' + token,
                'Content-Type': 'application/json',
            }
        });

        if (response.ok) {

            const result = await response.json();
            console.log(result);
            // Get the parent container where the project cards will be appended
            const projectContainer = document.querySelector('.project-area');

            // Iterate over the list of projects
            result.data.projects.forEach(project => {
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
                viewButton.href = `/project/${project.uuid}`;
                viewButton.textContent = 'View';

                // Create the delete button
                const deleteButton = document.createElement('a');
                deleteButton.classList.add('button');
                deleteButton.href = `/project/${project.uuid}/delete`;
                deleteButton.textContent = 'Delete';

                // Append the project name and buttons to the project card
                projectCard.appendChild(projectName);
                buttonArea.appendChild(viewButton);
                buttonArea.appendChild(deleteButton);
                projectCard.appendChild(buttonArea);

                // Append the project card to the parent container
                projectContainer.appendChild(projectCard);
            });


        } else {
            // Handle the error
            console.error(result.message);
        }
    } catch (error) {

    }

});