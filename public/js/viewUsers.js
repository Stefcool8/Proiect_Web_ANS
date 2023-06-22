document.addEventListener('DOMContentLoaded', async() => {
    const token = localStorage.getItem('jwt');
    const url = window.location.href;
    const pageRegex = /page=(\d+)/;
    const match = url.match(pageRegex);
    const page = match ? match[1] : "1";
    const pageSize = 4;
    console.log(page);
    if (!token) {
        window.location.href = "/login";
    }
    try {
        const response = await fetch('/api/auth/admin', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        const result = await response.json();
        console.log(result);

        if (response.status === 401) {
            // Unauthorized access, redirect to login page
            window.location.href = "/home";
            return;
        }
    } catch (error) {
        // Handle any errors
        console.error(error);
    }
    // fetch the users
    let $count;
    try {
        const response = await fetch('/api/user', {
            method: 'GET',
            headers: {
                Authorization: 'Bearer ' + token,
                'Content-Type': 'application/json',
            }
        });

        console.log(response);
        if(response.status === 404){
            console.log("No users found");
        }

        if (response.ok) {
            const result = await response.json();
            console.log(result);
            // Get the parent container where the user cards will be appended
            const alertCard = document.createElement('div');
            alertCard.classList.add('project');
            const introContainter = document.querySelector('.page-name');
            $count = result.data.data.length;

            if ($count === 0) {
                alertCard.textContent = "There is no user registered";
            } else if ($count === 1) {
                alertCard.textContent = "There is only one user registered";
            } else {
                alertCard.textContent = "There are " + $count + " users registered";
            }

            introContainter.appendChild(alertCard);

            const userContainer = document.querySelector('.user-list');

            try {
                const apiURL = "/api/user/page/" + page;
                const responseGetUserByPage = await fetch(apiURL, {
                    method: 'GET',
                    headers: {
                        Authorization: 'Bearer ' + token,
                        'Content-Type': 'application/json',
                    }
                });

                if (responseGetUserByPage.ok) {
                    const resultGetUserByPage = await responseGetUserByPage.json();
                    console.log(resultGetUserByPage);

                    resultGetUserByPage.data.users.forEach(user => {
                        // Create a new project card
                        const userCard = document.createElement('div');
                        userCard.classList.add('project');
                        userCard.classList.add(`project-${user.uuid}`); // Assign a unique class for this user

                        // Create the project name element
                        const userName = document.createElement('p');
                        userName.classList.add('project-name');
                        userName.textContent = user.username;

                        // Create the button area
                        const buttonArea = document.createElement('div');
                        buttonArea.classList.add('button-area');

                        // Create the view button
                        const viewButton = document.createElement('a');
                        viewButton.classList.add('button');
                        viewButton.textContent = 'View Profile';

                        viewButton.addEventListener('click', async (event) => {
                            event.preventDefault();
                            console.log('View Button clicked');
                            console.log('UUID:${user.uuid}');

                            try {
                                const apiUrl = `/api/user/${user.uuid}`;
                                const response = await fetch(apiUrl, {
                                    method: 'GET',
                                    headers: {
                                        Authorization: 'Bearer ' + token,
                                        'Content-Type': 'application/json',
                                    },
                                });

                                if (response.ok) {
                                    // The view was successful
                                    window.location.href = `/user/${user.uuid}`;
                                } else {
                                    // Handle the error
                                    console.error(result.message);
                                }
                            } catch (error) {
                                // Handle any errors
                                console.error(error);
                            }

                        });

                        // Create the view Projects button
                        const viewProjectsButton = document.createElement('a');
                        viewProjectsButton.classList.add('button');
                        viewProjectsButton.textContent = 'View Projects';

                        viewProjectsButton.addEventListener('click', async (event) => {
                            event.preventDefault();
                            console.log('ViewProjects Button clicked');
                            console.log('UUID:${user.uuid}');

                            try {
                                const apiUrl = `/api/project/user/${user.uuid}/1`;
                                const response = await fetch(apiUrl, {
                                    method: 'GET',
                                    headers: {
                                        Authorization: 'Bearer ' + token,
                                        'Content-Type': 'application/json',
                                    },
                                });

                                if (response.status === 404) {
                                    window.location.href = `/viewProjects/${user.uuid}?page=1`;
                                }

                                if (response.ok) {
                                    // The view was successful
                                    const URL = `/viewProjects/${user.uuid}?page=1`;
                                    console.log(URL);
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
                            console.log('Delete Button clicked');
                            console.log('UUID:${user.uuid}');

                            try {
                                const apiUrl = `/api/user/${user.uuid}`;
                                const response = await fetch(apiUrl, {
                                    method: "DELETE",
                                    headers: {
                                        Authorization: 'Bearer ' + token,
                                        'Content-Type': 'application/json',
                                    },
                                    body: user.uuid,
                                });

                                if (response.ok) {
                                    window.location.href = "/viewUsers";
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
                        userCard.appendChild(userName);
                        buttonArea.appendChild(viewButton);
                        buttonArea.appendChild(viewProjectsButton);
                        buttonArea.appendChild(deleteButton);
                        userCard.appendChild(buttonArea);

                        // Append the project card to the parent container
                        userContainer.appendChild(userCard);
                    });

                    const buttonPrevious = document.querySelector('.button-previous');
                    const buttonNext = document.querySelector('.button-next');

                    if (page === "1") {
                        buttonPrevious.classList.add('hidden');
                    } else {
                        buttonPrevious.classList.remove('hidden');
                    }

                    if (resultGetUserByPage.data.users.length < pageSize || $count <= pageSize*parseInt(page)) {
                        buttonNext.classList.add('hidden');
                    } else {
                        buttonNext.classList.remove('hidden');
                    }

                    buttonPrevious.addEventListener('click', async (event) => {
                        event.preventDefault();
                        window.location.href = `/viewUsers?page=${parseInt(page) - 1}`;
                    });

                    buttonNext.addEventListener('click', async (event) => {
                        event.preventDefault();
                        //console.log("Next button clicked");
                        window.location.href = `/viewUsers?page=${parseInt(page) + 1}`;
                    });
                }
                else {
                    // Handle the error
                    console.error(result.message);
                }
            } catch (error) {
                // Handle any errors
                console.error(error);
            }
        }
    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});

const deleteButtons = document.querySelectorAll(
    ".user-list .button-area a:last-child"
);

deleteButtons.forEach(function(button) {
    const token = localStorage.getItem('jwt');
    button.addEventListener("click", async(event) => {
        event.preventDefault();
        const userRow = event.target.closest(".project");
        const userData = JSON.parse(userRow.dataset.user);
        const uuid = userData.uuid;
        console.log("Deleted button clicked");
        console.log("UUID:", uuid);

        try {
            const apiUrl = "/api/user/" + uuid;
            const response = await fetch(apiUrl, {
                method: "DELETE",
                headers: {
                    Authorization: 'Bearer ' + token,
                    'Content-Type': 'application/json',
                },
                body: uuid,
            });

            if (response.ok) {
                window.location.href = "/viewUsers";
            } else {
                // Handle the error
                console.error(result.message);
            }
        } catch (error) {
            // Handle any errors
            console.error(error);
        }
    });
});

const userListContainer = document.querySelector(".user-list");

userListContainer.addEventListener("click", function(event) {
    // Check if the click occurred within the "user-list" region
    if (event.target.closest(".user-list")) {
        // Code to handle the click on the "user-list" region
        console.log("Clicked on the user-list region");
    }
});
