module.exports = {  
  mergeObject: function (objs) {
    var res = {}
    for (var i in objs) {
      var obj = objs[i]
      for (var k in obj) {
        res[k] = obj[k]
      }
    }
    return res
  }
}