document.addEventListener("DOMContentLoaded", async () => {
    // get the jwt token from local storage
    const token = localStorage.getItem("jwt");

    // if there is no token, return and do nothing
    if (!token) {
        return;
    }

    try {
        // send a request to the server to get the user's info
        const response = await fetch("/api/auth", {
            method: "GET",
            headers: {
                Authorization: "Bearer " + token,
                "Content-Type": "application/json",
            },
        });

        // if the response is not ok, return and do nothing
        if (!response.ok) {
            return;
        }

        const useNowLink = document.querySelector("a[href='/login']");
        const navRight = document.querySelector("#nav .right");

        if (!useNowLink || !navRight) {
            console.error("Required elements not found");
            return;
        }

        // update 'Use it now' link to 'Dashboard'
        useNowLink.href = "/dashboard";
        useNowLink.querySelector(".u-nav").textContent = "Dashboard";

        // create and append sign-out link
        const signOutLink = document.createElement("a");
        signOutLink.className = "nav-link";
        signOutLink.href = "/logout";
        signOutLink.innerHTML = `<span class="nav-link-span"><span class="u-nav">Sign Out</span></span>`;
        navRight.appendChild(signOutLink);

        // add event listener to the 'Sign Out' link, to remove the jwt token from local storage
        signOutLink.addEventListener("click", (e) => {
            e.preventDefault();
            localStorage.removeItem("jwt");
            localStorage.removeItem("user");
            window.location.href = "/";
        });
    } catch (error) {
        console.error(error);
    }
});

const $nav = $("#nav");

// utils for navbar
const util = {
    mobileMenu: () => $nav.toggleClass("nav-visible"),
    windowResize: () =>
        $(window).width() > 800 && $nav.removeClass("nav-visible"),
    scrollEvent: () => {
        const scrollPosition = $(document).scrollTop();

        $.each(util.scrollMenuIds, function (i) {
            const link = util.scrollMenuIds[i],
                container = $(link).attr("href"),
                containerOffset = $(container).offset().top,
                containerHeight = $(container).outerHeight(),
                containerBottom = containerOffset + containerHeight;

            $(link).toggleClass("active",scrollPosition < containerBottom - 20 && scrollPosition >= containerOffset - 20);
        });
    },
};

// add event listeners for navbar
$(document).ready(function () {
    util.scrollMenuIds = $("a.nav-link[href]");
    $("#menu").on("click", util.mobileMenu);
    $(window).on("resize", util.windowResize);
    $(document).on("scroll", util.scrollEvent);
});
