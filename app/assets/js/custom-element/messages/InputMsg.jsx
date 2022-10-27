/**
 * @author <akartis-dev>
 *
 * Do it with love
 */
import { Fragment, h } from "preact";
import { useEffect, useRef, useState } from "preact/hooks";
import "emoji-picker-element";
import axios from "axios";
import { url } from "../../url";

const InputMsg = ({ facebookId, setRefresh }) => {
  const [showEmoji, setShowEmoji] = useState(false);
  const emojiRef = useRef(null);
  const [value, setValue] = useState("");

  useEffect(() => {
    addListener();
  }, [showEmoji]);

  const addListener = () => {
    const emoji = emojiRef.current;
    if (emoji) {
      emoji.addEventListener("emoji-click", (event) => {
        setValue((value) => value + event?.detail?.unicode);
      });
    }
  };

  const handleChange = (e) => {
    setValue(e?.target?.value);
  };

  const reply = async () => {
    if (value?.trim()?.length > 0) {
      await axios.post(url?.message?.reply, { id: facebookId, content: value });
      setValue("");
      setRefresh((c) => !c);
    }
  };

  return (
    <Fragment>
      <div className="input-message">
        <input
          className="form-control"
          type="text"
          onInput={handleChange}
          value={value}
        />
        <button
          className="btn"
          id="input-emoji"
          onClick={() => setShowEmoji((c) => !c)}
        >
          <i className="fa fa-icons text-secondary" />
        </button>

        <button className="btn" id="input-send" onClick={reply}>
          <i className="fa fa-chevron-circle-right text-secondary" />
        </button>
      </div>
      {showEmoji && (
        <div className="d-flex justify-content-end">
          <emoji-picker ref={emojiRef} />
        </div>
      )}
    </Fragment>
  );
};

export default InputMsg;
