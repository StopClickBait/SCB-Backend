'use strict';

const NoAnswerFillerText = 'No submissions yet';

function isElementVisible(elem) {
  return elem.offsetParent !== null;
}

function isHeadline(elem) {
  if (elem.childElementCount === 0 && elem.innerText.length > 0) {
    var parent = elem.parentNode.parentNode;
    var siblings = Array.prototype.filter.call(parent.parentNode.children, function(child){
      return child !== parent;
    });
    for (var sibling of siblings) {
      if (sibling.tagName === "A") {
        return true;
      }
    }
  } else {
    return false;
  }
}

function openWebModal(event) {
  event.preventDefault();
  var link = event.currentTarget;
  picoModal(`
    <iframe height="640" width="900" src="${event.currentTarget.href}"></iframe>
  `).afterClose((modal) => {
    modal.destroy();
    console.log(link.getAttribute('data-realurl'));
    console.log(link.getAttribute('data-answernode-id'));
    chrome.runtime.sendMessage({ url: encodeURIComponent(link.getAttribute('data-realurl')), nodeId: link.getAttribute('data-answernode-id') });
  }).show();
  return false;
}

function prepare() {
  var css = document.createElement("style");
  css.type = "text/css";
  css.innerHTML = '.__clickbait_link {  } ';
  css.innerHTML += `.__clickbait_button {
    cursor: pointer;
    height: 1.8rem;
    letter-spacing: .1rem;
    line-height: 1.8rem;
    text-decoration: none;
    text-transform: uppercase;
    white-space: nowrap;
    display: inline-block;

    background-color: #f6f7f9;
    color: #4b4f56;

    border: 1px solid;
    border-radius: 2px;
    box-sizing: content-box;
    font-family: helvetica, arial, sans-serif;
    font-size: 12px;
    font-weight: bold;
    padding: 0 8px;
    position: relative;
    text-align: center;
    text-shadow: none;
    vertical-align: middle;
    border-color: #bbb;
  } `;
  css.innerHTML += `.__clickbait_text {
    display: inline-block;
    margin-left: 1em;
  } `;
  document.head.appendChild(css);
}

// counter that increments to generate a new ID
var uniqueIds = 0;

function loop() {
  console.log("running loop");
  var allLinks = document.querySelectorAll('a[onmouseover^="LinkshimAsyncLink"]');
  var qualifyingLinks = [];

  for (var node of allLinks) {
    if (isElementVisible(node) && isHeadline(node) && !node.classList.contains("__clickbait_link")) {
      console.log(node);
      node.classList.add("__clickbait_link");
      var realUrl = decodeURIComponent(node.href);
      realUrl = realUrl.substring(realUrl.indexOf('l.php?u=') + 'l.php?u='.length);
      realUrl = realUrl.substring(0, realUrl.indexOf('&h='));
      var containerDiv = document.createElement('div');
      containerDiv.classList.add('__clickbait_container_div');
      var spanContainer = node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
      spanContainer.insertBefore(containerDiv, spanContainer[1]);
      console.log(containerDiv);

      var btn = document.createElement('a');
      btn.classList.add('__clickbait_button');
      btn.href = `https://saveaclick.herokuapp.com/?url=${encodeURIComponent(realUrl)}`;
      btn.setAttribute('target', '__blank');
      btn.innerText = 'Open';
      btn.addEventListener("click", openWebModal, true);
      
      // node.parentNode.appendChild(btn);

      var answerNode = document.createElement("p");
      answerNode.classList.add('__clickbait_text');
      answerNode.innerText = "#StopClickBait";
      answerNode.id = `__clickbait_ids_${(uniqueIds++)}`;
      // node.parentNode.appendChild(answerNode);

      btn.setAttribute('data-answernode-id', answerNode.id);
      btn.setAttribute('data-realurl', realUrl);

      containerDiv.appendChild(btn);
      containerDiv.appendChild(answerNode);

      chrome.runtime.sendMessage({ url: encodeURIComponent(realUrl), nodeId: answerNode.id });
    }
  }
}

chrome.runtime.onMessage.addListener(function onDataFetched(message) {
  document.getElementById(message.nodeId).innerText = message.answer.answer;
});

function init() {
  prepare();
  document.addEventListener("scroll", loop, false);
  loop();
}

init();

/**
 * TODOs:
 * Add throttling to scroll event
 * create a web service to store clickbait answers
 * create a producthunt type page to list answers and add a new one
 */

// temp1.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode