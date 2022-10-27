import { h } from "preact";
import { renderPreact } from "../render";
import IncomingMsg from "./IncomingMsg";
import OutgoingMsg from "./OutgoingMsg";
import { useEffect, useState } from "preact/hooks";
import axios from "axios";
import InputMsg from "./InputMsg";
import { url } from "../../url";

const ReplyMessage = ({ facebookId }) => {
  const [messages, setMessages] = useState([]);
  const [refresh, setRefresh] = useState(false);

  useEffect(() => {
    getMessage();
  }, [refresh]);

  const getMessage = async () => {
    const result = await axios.post(url?.message?.getCustomer, {
      id: facebookId,
    });
    setMessages(result.data);
  };

  return (
    <div>
      <div style={{ height: 400, overflowY: "scroll" }}>
        {messages?.map((e, i) =>
          e?.sender ? (
            <IncomingMsg date={e?.createdAt} text={e?.content} />
          ) : (
            <OutgoingMsg date={e?.createdAt} text={e?.content} />
          )
        )}
      </div>
      <InputMsg facebookId={facebookId} setRefresh={setRefresh} />
    </div>
  );
};

renderPreact(ReplyMessage, "reply-message");
