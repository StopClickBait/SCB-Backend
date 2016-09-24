
chrome.runtime.onMessage.addListener(function fetcher(message, sender) {
  // console.log('fetcher got message', message);
  fetch(`http://saveaclick.herokuapp.com/api/answers?url=${message.url}`).then(response => {
    if (response.ok) {
      return response.json();
    }
  }).then((data) => {
    if (data) {
      var answer = data.answers[0];
      if (answer) {
        chrome.tabs.sendMessage(sender.tab.id, { nodeId: message.nodeId, answer: answer });
      }
    }
  }).catch(err => {
    console.error(err);
  });
})