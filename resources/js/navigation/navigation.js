$(document).ready(function () {
    let currentSidebarName = $(
        ".sidebar__current-point .nav-link-title"
    ).text();
    $(".bg__header--name").text(`/${currentSidebarName}`);
});
