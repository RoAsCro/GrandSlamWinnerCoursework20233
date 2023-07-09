
 // Function for getting and displaying the results from JSON data retrieved from iwt-cw.php.
 // The function clears the existing results from the HTML page, retrieve the arguments from that page,
 // passes them to the PHP file in the form of query string parameters, gets the resulting JSON,
 // and displays them on the original HTML page.
function getResults() {
    // Clear existing results on the HTML page
    clearResults();
    const file = document.getElementById("file").value + "-grand-slam-winners.json";
    const tournament = document.getElementById("tournament").value;
    const year = document.getElementById("year").value;
    const yearOp = document.getElementById("year-op").value;
    const winner = document.getElementById("winner").value;
    const runnerUp = document.getElementById("runner-up").value;
    
    // Get the JSON file
    $.getJSON(
        "iwt-cw.php", {
            file: file,
            tournament: tournament,
            yearOp: yearOp,
            year: year,
            winner: winner,
            runnerUp: runnerUp
        },
            function (data) {  
                // Checks if the PHP returns an error
                if (data.hasOwnProperty("error")) {
                    $("#output").append($("<p>" + data.error + "</p>"));
                } 
                // Checks if no data was retrieved
                else if (data.length == 0) {
                    $("#output").append($("<p>" +
                    "No results found with given parameters." +
                    "</p>"));
                } 
                else {
                    // Concatenate the JSON results into a table
                    let tableString = "";
                    tableString +=
                    "<table id='resultTable'>" +
                    "<thead><tr>" +
                    "<th>Year</th> <th>Tournament</th> <th>Winner</th> <th>Runner Up</th>" +
                    "</tr></thead>";

                    for (let i=0 ; i < data.length ; i++) {
                        tableString +=
                        "<tr>" +
                        "<td>" + data[i].year + "</td>" +
                        "<td>" + data[i].tournament + "</td>" +
                        "<td>" + data[i].winner + "</td>" +
                        "<td>" + data[i]["runner-up"] + "</td>" +
                        "</tr>";
                    }
                    tableString += "</table>";

                    $("#output").append($(tableString));
                }
            }
    )

}

// Clear the results on the page
function clearResults() {
    $("#output").empty();
}

// Clear the contents of the form
function clearForm() {
    document.getElementById("searchForm").reset();
}

window.onload = function () {
    document.getElementById("send-query").onclick = getResults;
    document.getElementById("clear-output").onclick = clearResults;
    document.getElementById("clear-input").onclick = clearForm;
}