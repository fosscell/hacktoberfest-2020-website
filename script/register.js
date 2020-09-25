const rootElem = document.documentElement;
const pageOneElem = document.querySelector("main .right .page.one");
const pageTwoElem = document.querySelector("main .right .page.two");
const registerFormElem = pageOneElem.querySelector("form");
const submitButtonElem = registerFormElem.querySelector(
  "form button[type=submit]"
);
const submitButtonContainerElem = registerFormElem.querySelector(
  "form .button-container"
);
const messageElem = registerFormElem.querySelector(".message");

let loading = false;

registerFormElem.addEventListener("submit", async (e) => {
  e.preventDefault();

  if (loading) return;

  loading = true;
  const animate = submitButtonElem.animate(
    {
      opacity: [1, 0],
    },
    {
      duration: 300,
      easing: "ease-in-out",
    }
  );

  let loader;
  animate.addEventListener("finish", async () => {
    submitButtonElem.style.opacity = 0;
    submitButtonElem.style.display = "none";
    loader = addLoader(submitButtonContainerElem, true);

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const rollNo = document.getElementById("rollno").value;
    const phone = document.getElementById("phone").value;
    const projects = document.getElementById("projects").value;
    const captcha = document.getElementById("captcha").value;

    const details = { name, email, rollNo, phone, projects, captcha };

    try {
      const res = await fetch("/api/register.php", {
        method: "POST",
        body: JSON.stringify(details),
      });

      const msg = await res.json();

      if (res.status !== 200) {
        console.log(msg.message);
        throw msg.message;
      } else {
        showSuccess();
      }
    } catch (e) {
      messageElem.innerHTML = e || "Some error occured";
      console.error(e);
      loader.parentElement.removeChild(loader);
      submitButtonElem.style.display = "flex";
      const animate = submitButtonElem.animate(
        {
          opacity: [0, 1],
        },
        {
          duration: 300,
          easing: "ease-in-out",
        }
      );

      animate.addEventListener("finish", () => {
        submitButtonElem.style.opacity = 1;
      });
      loading = false;
    }
  });
});

const showSuccess = () => {
  pageOneElem.style.position = "absolute";
  const animate1 = pageOneElem.animate(
    {
      opacity: [1, 0],
    },
    {
      duration: 300,
      easing: "ease-in-out",
    }
  );

  animate1.addEventListener("finish", () => {
    pageOneElem.style.display = "none";
    pageTwoElem.style.display = "block";
    pageOneElem.style.opacity = 0;
    pageTwoElem.style.opacity = 1;
    const animate2 = pageTwoElem.animate(
      {
        opacity: [0, 1],
      },
      {
        duration: 300,
        easing: "ease-in-out",
      }
    );
  });
};

const addLoader = (parent, prepend) => {
  const loader = el("DIV", ["lds-ellipsis"], "", parent, prepend);
  el("div", [], "", loader);
  el("div", [], "", loader);
  el("div", [], "", loader);
  el("div", [], "", loader);
  return loader;
};

const el = (
  type,
  classNames = [],
  content = "",
  parent = document.body,
  prepend = false
) => {
  const el = document.createElement(type);
  classNames.forEach((className) => el.classList.add(className));
  el.appendChild(document.createTextNode(content));
  if (prepend) {
    parent.prepend(el);
  } else {
    parent.append(el);
  }
  return el;
};
