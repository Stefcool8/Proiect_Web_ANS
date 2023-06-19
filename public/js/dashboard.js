document.addEventListener('DOMContentLoaded', dashboard);

async function dashboard() {
    const token = getToken();
    if (!token) redirectToLogin();

    const urlParams = new URLSearchParams(window.location.search);
    let page = getPage(urlParams);
    const pageSize = 4;

    const uuid = await fetchUserUUID(token);
    if (!uuid) return;

    const projectCount = await fetchUserProjectCount(token, uuid);
    if (!projectCount && page > 1) redirectToDashboardPage(1);

    const projects = await fetchUserProjects(token, uuid, page, pageSize);
    if (!projects) return;

    displayProjects(projects, token);

    handlePageButtons(page, projectCount, pageSize);
}

function getToken() {
    return localStorage.getItem('jwt');
}

function redirectToLogin() {
    window.location.href = "/login";
}

function getPage(urlParams) {
    let page = urlParams.get('page') || "1";
    if (page < 1) {
        window.location.href = "/dashboard";
    }
    return page;
}

async function fetchUserUUID(token) {
    try {
        const response = await fetch('/api/dashboard', {
            method: 'GET',
            headers: createHeaders(token),
        });

        if (response.status === 401) redirectToLogin();

        const result = await response.json();
        const uuid = result.data.data.uuid;

        updateUserInterface(result, uuid);

        return uuid;
    } catch (error) {
        console.error(error);
        return null;
    }
}

function createHeaders(token) {
    return {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json',
    };
}

function updateUserInterface(result, uuid) {
    const adminPanel = document.querySelector(".admin-panel-btn");
    result.data.data.isAdmin ? adminPanel.classList.remove('hidden') : adminPanel.classList.add('hidden');

    document.querySelector('.page-name p').textContent = 'Dashboard, Hello ' + result.data.data.username;
    document.querySelector(".view-profile-btn").href = "/user/" + uuid;
}

async function fetchUserProjectCount(token, uuid) {
    try {
        const response = await fetch(`/api/project/user/${uuid}`, {
            method: 'GET',
            headers: createHeaders(token),
        });

        if (response.ok) {
            return (await response.json()).data.projects.length;
        } else {

            handleError(response.status);
            return null;
        }
    } catch (error) {
        console.error(error);
        return null;
    }
}

function handleError(status) {
    if (status === 404) {
        const projectContainer = document.querySelector('.project-area');
        const alertCard = document.createElement('div');
        alertCard.classList.add('project');
        alertCard.textContent = "This user has no projects";
        projectContainer.appendChild(alertCard);

        hideNavigationButtons();
    } else {
        redirectToHome();
    }
}

function hideNavigationButtons() {
    const buttonPrevious = document.querySelector('.button-previous');
    const buttonNext = document.querySelector('.button-next');

    buttonPrevious.classList.add('hidden');
    buttonNext.classList.add('hidden');
}

function redirectToHome() {
    window.location.href = "/home";
}

async function fetchUserProjects(token, uuid, page, pageSize) {
    try {
        const response = await fetch(`/api/project/user/${uuid}/${page}`, {
            method: 'GET',
            headers: createHeaders(token),
        });

        if(response.status === 404) {
            window.location.href = "/dashboard";
        }

        return response.ok ? await response.json() : null;
    } catch (error) {
        console.error(error);
        return null;
    }
}

function displayProjects(result, token) {
    const projectContainer = document.querySelector('.project-area');

    result.data.projects.forEach(project => {
        const projectCard = createProjectCard(project, token);
        projectContainer.appendChild(projectCard);
    });
}

function createProjectCard(project, token) {
    const projectCard = document.createElement('div');
    projectCard.classList.add('project', `project-${project.uuid}`);

    const projectName = document.createElement('p');
    projectName.classList.add('project-name');
    projectName.textContent = project.name;

    const buttonArea = document.createElement('div');
    buttonArea.classList.add('button-area');

    const viewButton = createButton('View', createViewEvent(project, token));
    const deleteButton = createButton('Delete', createDeleteEvent(project, token));

    buttonArea.append(viewButton, deleteButton);
    projectCard.append(projectName, buttonArea);

    return projectCard;
}

function createButton(text, clickEvent) {
    const button = document.createElement('a');
    button.classList.add('button');
    button.textContent = text;
    button.addEventListener('click', clickEvent);
    return button;
}

function createViewEvent(project, token) {
    return async (event) => {
        event.preventDefault();

        const responseGet = await fetch(`/api/project/${project.uuid}`, {
            method: 'GET',
            headers: createHeaders(token),
        });

        if (responseGet.ok) {
            window.location.href = `/project/${project.uuid}`;
        } else {
            console.error(result.message);
        }
    };
}

function createDeleteEvent(project, token) {
    return async (event) => {
        event.preventDefault();

        // get user confirmation
        const response = confirm(`Are you sure you want to delete ${project.name}?`);
        if (!response) {
            return;
        }
        
        const responseDELETE = await fetch(`/api/project/${project.uuid}`, {
            method: "DELETE",
            headers: createHeaders(token),
            body: project.uuid,
        });

        if (responseDELETE.ok) {
            window.location.href = "/dashboard";
        } else {
            console.error(result.message);
        }
    };
}

function handlePageButtons(page, projectCount, pageSize) {
    const buttonPrevious = document.querySelector('.button-previous');
    const buttonNext = document.querySelector('.button-next');

    toggleVisibility(buttonPrevious, page !== "1");
    toggleVisibility(buttonNext, projectCount > pageSize * parseInt(page));

    buttonPrevious.addEventListener('click', createNavigationEvent(page, -1));
    buttonNext.addEventListener('click', createNavigationEvent(page, 1));
}

function toggleVisibility(element, isVisible) {
    if (isVisible) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}

function createNavigationEvent(page, direction) {
    return (event) => {
        event.preventDefault();
        redirectToDashboardPage(parseInt(page) + direction);
    };
}

function redirectToDashboardPage(page) {
    window.location.href = `/dashboard?page=${page}`;
}
