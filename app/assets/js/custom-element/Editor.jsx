import { h } from "preact";
import { useEffect, useRef, useState } from "preact/hooks";
import "emoji-picker-element";
import { renderPreact } from "./render";

const Editor = () => {
  const emojiRef = useRef(null);
  const textAreaRef = useRef(null);
  const [showEmoji, setShowEmoji] = useState(false);
  const [value, setValue] = useState("");

  useEffect(() => {
    addListener();
  }, [showEmoji]);

  useEffect(() => {
    const textarea = document.querySelector(".message-content");
    textarea.innerHTML = value;
  }, [value]);

  const addListener = () => {
    const emoji = emojiRef.current;
    if (emoji) {
      emoji.addEventListener("emoji-click", (event) => {
        setValue((value) => value + event?.detail?.unicode);
      });
    }
  };

  const handleChange = () => {
    setValue(textAreaRef?.current?.value);
  };

  return (
    <div>
      <textarea
        ref={textAreaRef}
        className="form-control"
        cols={6}
        rows={6}
        value={value}
        onKeyUp={handleChange}
      />
      <div className="text-right mt-1">
        <button
          className="btn btn-outline-light shadow-none"
          type="button"
          onClick={() => setShowEmoji((open) => !open)}
        >
          <i className="fa fa-icons text-dark" style={{ fontSize: 20 }} />
        </button>
        {showEmoji && (
          <div className="d-flex justify-content-end">
            <emoji-picker ref={emojiRef} />
          </div>
        )}
      </div>
    </div>
  );
};

renderPreact(Editor, "app-editor");
