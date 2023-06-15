document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("jwt");

    if (!token) {
        return;
    }

    try {
        const response = await fetch("/api/auth", {
            method: "GET",
            headers: {
                Authorization: "Bearer " + token,
                "Content-Type": "application/json",
            },
        });

        if (!response.ok) {
            return;
        }

        const useNowLink = document.querySelector("a[href='/login']");
        const navRight = document.querySelector("#nav .right");

        if (!useNowLink || !navRight) {
            console.error("Required elements not found");
            return;
        }

        useNowLink.href = "/dashboard";
        useNowLink.querySelector(".u-nav").textContent = "Dashboard";

        const signOutLink = document.createElement("a");
        signOutLink.className = "nav-link";
        signOutLink.href = "/logout";
        signOutLink.innerHTML = `<span class="nav-link-span"><span class="u-nav">Sign Out</span></span>`;
        navRight.appendChild(signOutLink);

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
const util = {
    mobileMenu: () => $nav.toggleClass("nav-visible"),
    windowResize: () => $(window).width() > 800 && $nav.removeClass("nav-visible"),
    scrollEvent: () => {
        const scrollPosition = $(document).scrollTop();

        $.each(util.scrollMenuIds, function (i) {
            const link = util.scrollMenuIds[i];
            const href = $(link).attr("href");
            const container = $("#" + href.substring(1));

            if(container.length) {
                const containerOffset = container.offset().top;
                const containerHeight = container.outerHeight();
                const containerBottom = containerOffset + containerHeight;

                $(link).toggleClass(
                    "active",
                    scrollPosition < containerBottom - 20 &&
                    scrollPosition >= containerOffset - 20
                );
            }
        });
    },
};

$(document).ready(function () {
    util.scrollMenuIds = $("a.nav-link[href]");
    $("#menu").on("click", util.mobileMenu);
    $(window).on("resize", util.windowResize);
    $(document).on("scroll", util.scrollEvent);
});