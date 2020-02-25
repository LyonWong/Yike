var customElemMap = {
	'IMAGE': 'image',
	'SOUND': 'audio',
	'VIDEO': 'video',
	'QUOTE': 'text'
}
var TIM = {
	parse: function(message) {
//	console.log('message', message)
	var content = {}
		switch (message.MsgType) {
			case 'TIMTextElem':
				content = {
					'type': 'text',
					'data': message.MsgContent.Text
				}
			break;
			case 'TIMCustomElem':
				content = {
				 'type': customElemMap[message.MsgContent.Desc] || message.msgContent.Desc,
				 'data': message.MsgContent.Data
				 }
			break;
		}
		return content
	}
}

module.exports = TIM
