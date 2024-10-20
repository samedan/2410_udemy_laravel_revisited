import "./bootstrap";
import Chat from "./chat";
import Search from "./live-search";

// Load search only if page contains Search icon
if (document.querySelector(".header-search-icon")) {
    new Search();
}

// Load search only if page contains CHat icon
if (document.querySelector(".header-chat-icon")) {
    new Chat();
}
