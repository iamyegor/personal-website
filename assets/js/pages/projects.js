const projectsData = [
  {
    name: "Reform Proposal Hub", // Example name
    previewImage: "reform-hub/preview.webp",
    link: "projects/reform-hub.html", // Link to the specific project detail page
  },
  {
    name: "NetIQ - AI Chat",
    previewImage: "netiq/preview.webp",
    link: "projects/netiq.html",
  },
  {
    name: "Visa Appointments Booking Bot",
    previewImage: "nepal-to-korea/preview.webp",
    link: "projects/visa-bot.html",
  },
  {
    name: "Krijji - E-commerce Store",
    previewImage: "krijji/preview.webp",
    link: "projects/krijji.html",
  },

  {
    name: "Attire - E-commerce Store",
    previewImage: "attire/preview.webp",
    link: "projects/attire.html",
  },
  {
    name: "Duchess - Gym Website",
    previewImage: "duchess/preview.webp",
    link: "projects/duchess.html",
  },
  {
    name: "OditGroup - Intro Page",
    previewImage: "oditgroup/preview.webp",
    link: "projects/oditgroup.html",
  },
  {
    name: "TopPoizon - Landing Page",
    previewImage: "toppoizon/preview.webp",
    link: "projects/toppoizon.html",
  },
]

function displayProjects() {
  const projectGridContainer = document.getElementById("project-grid")

  // Check if the container element exists
  if (!projectGridContainer) {
    console.error("Error: Could not find element with ID 'project-grid'")
    return // Exit if the container isn't found
  }

  // Use map to create an array of HTML strings, one for each project
  const projectsHTML = projectsData
    .map(project => {
      return `
        <div class="col-12 col-md-6 col-lg-4" data-aos="flip-down"> 
          <div class="overflow-hidden position-relative project-item">
            <img
              class="project-item-img" 
              src="assets/images/portfolios/${project.previewImage}"
              alt="${project.name} Preview" 
            />
            <div class="position-absolute project-item-content">
              <div
                class="d-flex flex-wrap align-items-center justify-content-between project-item-contet-wrap"
              >
                <h4
                  class="fw-bold font-Syne text-center leading-10 project-title"
                >
                  <a class="transition-all" href="${project.link}">
                    ${project.name} 
                  </a>
                </h4>
                <a href="${project.link}" class="animate-arrow-up">
                  <svg
                    width="40"
                    height="40"
                    viewBox="0 0 40 40"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                     <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M30.8839 9.11612C31.372 9.60427 31.372 10.3957 30.8839 10.8839L10.8839 30.8839C10.3957 31.372 9.60427 31.372 9.11612 30.8839C8.62796 30.3957 8.62796 29.6043 9.11612 29.1161L29.1161 9.11612C29.6043 8.62796 30.3957 8.62796 30.8839 9.11612Z"
                      fill="currentColor"
                      fill-opacity="0.9"
                    />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.5 10C12.5 9.30964 13.0596 8.75 13.75 8.75H30C30.6904 8.75 31.25 9.30964 31.25 10V26.25C31.25 26.9404 30.6904 27.5 30 27.5C29.3096 27.5 28.75 26.9404 28.75 26.25V11.25H13.75C13.0596 11.25 12.5 10.6904 12.5 10Z"
                      fill="currentColor"
                      fill-opacity="0.9"
                    />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      `
    })
    .join("") // Join the array of HTML strings into a single string

  projectGridContainer.innerHTML = projectsHTML
}

displayProjects()
