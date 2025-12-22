window.onload = (event) => {
  console.log("page is fully loaded");
};


for (let i = 0; i < cars.length; i++) {
  text += cars[i] + "<br>";
}



// Fetch API
const apiUrl = "<?= $base_url ?>index.cgi"
var formData = new FormData()
formData.append("a", "concert-api-addTimesheet")
formData.append("x", e$("x").value)

bdPostData(apiUrl, formData).then((response) => {
    
    console.log(response);
    
    if (response[0] == "T") {
        console.log("Added.")
        
        // window.location.reload()
        
        
    } else {
        dxAlertBox("Timesheet Add Error", response[1])
    }
});



if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(
    (position) => {
      console.log("Latitude:", position.coords.latitude);
      console.log("Longitude:", position.coords.longitude);
    },
    (error) => {
      console.error("Error getting location:", error.message);
    }
  );
} else {
  console.log("Geolocation not supported by this browser.");
}
