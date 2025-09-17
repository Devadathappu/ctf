// Submit a flag
async function submitFlag(flagValue) {
    const response = await fetch('/flag.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ flag: flagValue })
    });
    const result = await response.json();
    console.log(result.message);
}

// Fetch user points
async function fetchPoints() {
    const response = await fetch('/points_api.php');
    const result = await response.json();
    console.log(`Your total points: ${result.points}`);
}