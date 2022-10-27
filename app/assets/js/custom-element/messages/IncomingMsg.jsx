/**
 * @author <akartis-dev>
 *
 * Do it with love
 */
import { h } from "preact";

const IncomingMsg = ({ text, date }) => {
  return (
    <div className="incoming_msg">
      <div className="received_msg">
        <div className="received_withd_msg">
          <p>{text}</p>
          <span className="time_date">
            {new Date(date).toLocaleString("fr")}
          </span>
        </div>
      </div>
    </div>
  );
};

export default IncomingMsg;
