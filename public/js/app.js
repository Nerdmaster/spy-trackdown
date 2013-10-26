// Really horrible hard-coded list of commands - this should really be generated by the app or a
// config file the app uses or something.  At the least, travel codes should be shared instead of
// doubly hard-coded.
var commandList = {
  // Movement (or lack thereof)
  "OOOO": {"text": "Skip action", "action": "skip_action"},
  "XOXO": {"text": "Travel by Sports Car (red)", "action": "travel0"},
  "XOOO": {"text": "Travel by Helicopter (blue)", "action": "travel1"},
  "OXOX": {"text": "Travel by Motorcycle (green)", "action": "travel2"},
  "OXXX": {"text": "Travel by Jet (yellow)", "action": "travel3"},

  // TODO: Covert actions
};

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

// Shows a modal dialog when a command is entered to get confirmation from user
function showConfirm(actionData) {
  if (!actionData) {
    alert("Invalid command entered.");
    return;
  }

  // TODO: Make the "confirm" action post to server
  $("#confirm-command-label").html(actionData.text);
  $("#confirm-command").modal();
}

// Does lookup for commands, and if the command is valid, asks for confirmation
function checkCommand() {
  var command = currentCommand();
  if (command.length == 4) {
    resetCommand();
    showConfirm(commandList[command]);
  }
}

$(function() {
  // Attach listeners to phone buttons
  $("#phone .phone-button.command button").on("click", function() {
    addToCommand($(this).data("value"));
    checkCommand();
  });

  $("#phone .phone-button.reset button").on("click", function() {
    resetCommand();
  });
});
