/* ============================================================
   MOBILE NAVIGATION
============================================================ */

const menuToggle =
    document.querySelector(".menu-toggle");

const nav =
    document.querySelector(".nav");


if (menuToggle && nav) {

    menuToggle.addEventListener("click", () => {

        const isOpen =
            nav.classList.toggle("open");


        menuToggle.setAttribute(
            "aria-expanded",
            isOpen
        );


        if (isOpen) {

            nav.style.display = "flex";

            nav.style.position = "absolute";

            nav.style.top = "82px";

            nav.style.left = "0";

            nav.style.right = "0";

            nav.style.height = "auto";

            nav.style.padding = "20px";

            nav.style.background =
                "#003f31";

            nav.style.flexDirection =
                "column";

            nav.style.alignItems =
                "stretch";

            nav.style.gap = "18px";

            nav.style.zIndex = "20";


            nav.querySelectorAll("a")
                .forEach(link => {

                    link.style.height =
                        "auto";

                });

        } else {

            closeMenu();

        }

    });

}


/* ============================================================
   CLOSE MOBILE MENU
============================================================ */

function closeMenu() {

    if (!nav || !menuToggle) {
        return;
    }


    nav.classList.remove("open");

    nav.removeAttribute("style");

    menuToggle.setAttribute(
        "aria-expanded",
        "false"
    );

}


/* ============================================================
   CLOSE MENU AFTER CLICKING A LINK
============================================================ */

if (nav) {

    nav.querySelectorAll("a")
        .forEach(link => {

            link.addEventListener(
                "click",
                () => {

                    if (
                        window.innerWidth <= 900
                    ) {

                        closeMenu();

                    }

                }
            );

        });

}


/* ============================================================
   RESET NAVIGATION WHEN RESIZING
============================================================ */

window.addEventListener(
    "resize",
    () => {

        if (
            window.innerWidth > 900
        ) {

            closeMenu();

        }

    }
);


/* ============================================================
   ACTIVE NAVIGATION LINK
============================================================ */

const navLinks =
    document.querySelectorAll(
        ".nav a"
    );


navLinks.forEach(link => {

    link.addEventListener(
        "click",
        () => {

            navLinks.forEach(
                item => {
                    item.classList.remove(
                        "active"
                    );
                }
            );


            link.classList.add(
                "active"
            );

        }
    );

});