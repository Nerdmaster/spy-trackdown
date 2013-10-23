// Displays current command in phone "terminal"
function displayPhoneCommand() {
  var command = $("#phone-display").data("command");
  $("#phone-display").html(command + "_");
}

// Resets the phone command to a blank string
function resetCommand() {
  $("#phone-display").data("command", "");
}

// Processes button presses, adding to the command string and showing a confirmation button
// if the string maps to a full command
function addToCommand(val) {
  var command = $("#phone-display").data("command");
  $("#phone-display").data("command", command + val);
  // TODO: Do some lookup for commands, and if it's valid offer a "confirm" button
}

$(function() {
  // Attach listeners to phone buttons
  $("#phone .phone-button.command button").on("click", function() {
    addToCommand($(this).data("value"));
    displayPhoneCommand();
  });
  $("#phone .phone-button.reset button").on("click", function() {
    resetCommand();
    displayPhoneCommand();
  });
});
