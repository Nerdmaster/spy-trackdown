// Returns the currently entered command on the phone
function currentCommand() {
  return $("#phone-display").data("command");
}

// Displays current command in phone "terminal"
function displayPhoneCommand() {
  $("#phone-display").html(currentCommand() + "_");
}

// Resets the phone command to a blank string
function resetCommand() {
  $("#phone-display").data("command", "");
  displayPhoneCommand();
}

// Processes button presses, adding to the command string and showing a confirmation button
// if the string maps to a full command
function addToCommand(val) {
  $("#phone-display").data("command", currentCommand() + val);
  displayPhoneCommand();
}

$(function() {
  // Attach listeners to phone buttons
  $("#phone .phone-button.command button").on("click", function() {
    addToCommand($(this).data("value"));
  });

  $("#phone .phone-button.reset button").on("click", function() {
    resetCommand();
  });
});
