export function initializeCTF() {
  // Simulated data (replace with actual data from your backend)
  const challenges = [
    { id: 1, title: "Web Exploitation 101", points: 100, difficulty: "Easy" },
    { id: 2, title: "Cryptography Challenge", points: 200, difficulty: "Medium" },
    { id: 3, title: "Reverse Engineering", points: 300, difficulty: "Hard" },
  ]

  const leaderboardData = [
    { username: "hacker1", points: 500 },
    { username: "securitypro", points: 450 },
    { username: "ctfmaster", points: 400 },
  ]

  // Function to show a specific page
  function showPage(pageId) {
    document.querySelectorAll("#loginPage, #dashboardPage, #challengesPage").forEach((page) => {
      page.style.display = "none"
    })
    document.getElementById(pageId).style.display = "block"
  }

  // Event listener for login form
  document.getElementById("loginForm").addEventListener("submit", (e) => {
    e.preventDefault()
    const username = document.getElementById("username").value
    const password = document.getElementById("password").value

    // Send login request to the server
    fetch("login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showPage("dashboardPage")
          fetchUserData()
        } else {
          alert("Login failed. Please try again.")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        alert("An error occurred. Please try again.")
      })
  })

  // Event listeners for navigation
  document.querySelectorAll("[data-page]").forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()
      showPage(this.dataset.page + "Page")
    })
  })

  // Logout functionality
  document.getElementById("logoutBtn").addEventListener("click", (e) => {
    e.preventDefault()
    fetch("logout.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showPage("loginPage")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
      })
  })

  // Populate challenges
  function populateChallenges() {
    const challengesList = document.getElementById("challengesList")
    challengesList.innerHTML = challenges
      .map(
        (challenge) => `
        <div class="col-md-4 mb-4">
            <div class="card challenge-card">
                <div class="card-body">
                    <h5 class="card-title">${challenge.title}</h5>
                    <p class="card-text">Points: ${challenge.points}</p>
                    <p class="card-text">Difficulty: ${challenge.difficulty}</p>
                    <button class="btn btn-primary" onclick="solveChallenge(${challenge.id})">Solve Challenge</button>
                </div>
            </div>
        </div>
    `,
      )
      .join("")
  }

  // Populate leaderboard
  function populateLeaderboard() {
    const leaderboard = document.getElementById("leaderboard")
    leaderboard.innerHTML = leaderboardData
      .map(
        (user) => `
        <li class="leaderboard-item">${user.username} - ${user.points} points</li>
    `,
      )
      .join("")
  }

  // Fetch user data
  function fetchUserData() {
    fetch("user_data.php")
      .then((response) => response.json())
      .then((data) => {
        document.getElementById("completedChallenges").textContent = data.completedChallenges
        document.getElementById("totalPoints").textContent = data.totalPoints
      })
      .catch((error) => {
        console.error("Error:", error)
      })
  }

  // Solve challenge
  function solveChallenge(challengeId) {
    fetch("solve_challenge.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `challenge_id=${challengeId}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Challenge solved successfully!")
          fetchUserData()
        } else {
          alert("Failed to solve the challenge. Try again!")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        alert("An error occurred. Please try again.")
      })
  }

  // Initialize pages
  populateChallenges()
  populateLeaderboard()
}

if (typeof window !== "undefined") {
  initializeCTF()
}

