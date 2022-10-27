/**
 * @author <akartis-dev>
 *
 * Do it with love
 */
import { h } from "preact";

const OutgoingMsg = ({ text, date }) => {
  return (
    <div className="outgoing_msg">
      <div className="sent_msg">
        <p>{text}</p>
        <span className="time_date">{new Date(date).toLocaleString("fr")}</span>
      </div>
    </div>
  );
};

export default OutgoingMsg;
