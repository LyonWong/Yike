<template>
  <div class="c-markdown-guide">
    <div v-for="(tip, idx) in tips" :key="idx" class="tip">
      <div class="box">
        <textarea  v-model="tips[idx]" :rows="tip | rows"></textarea>
      </div>
      <c-section :content="{type: 'markdown', text: tip}"></c-section>
    </div>
  </div>
</template>

<script>
  import CSection from "../../components/section/index";
  export default {
    name: 'markdown-guide',
    components: {CSection},
    data() {
      return {
        tips: [
          '### 1. 斜体、粗体和删除\n' +
          '\n' +
          '使用 * 和 ** 表示斜体和粗体。\n' +
          '\n' +
          '使用 ~~ 表示删除。\n' +
          '\n' +
          '示例：\n' +
          '\n' +
          '这是 *斜体*，这是 **粗体**，这是 ~~删除~~',
          '### 2. 分级标题\n' +
          '\n' +
          '使用 # 来表示标题等级\n' +
          '\n' +
          '示例：\n' +
          '\n' +
          '# 这是一个一级标题\n' +
          '\n' +
          '## 这是一个二级标题\n' +
          '\n' +
          '### 这是一个三级标题',
          '### 3. 外链接\n' +
          '\n' +
          '使用 \\[描述](链接地址) 为文字增加外链接。\n' +
          '\n' +
          '示例：\n' +
          '\n' +
          '这是去往 [易灵微课](https://yike.fm/lesson/) 的链接。',
          '### 4. 无序列表\n' +
          '\n' +
          '使用 *，+，- 表示无序列表。\n' +
          '\n' +
          '示例：\n' +
          '\n' +
          '- 无序列表项 一\n' +
          '- 无序列表项 二\n' +
          '- 无序列表项 三',
          '### 5. 有序列表\n' +
          '\n' +
          '使用数字和点表示有序列表。\n' +
          '\n' +
          '示例：\n' +
          '\n' +
          '1. 有序列表项 一\n' +
          '2. 有序列表项 二\n' +
          '3. 有序列表项 三',
          '### 6. 文字引用\n' +
          '\n' +
          '使用 > 表示文字引用。\n' +
          '\n' +
          '示例：\n' +
          '\n' +
          '> 三人行，必有我师焉。',
          '### 7. 行内代码块\n' +
          '\n' +
          '使用 \\`代码` 表示行内代码块。\n' +
          '\n' +
          '示例：\n' +
          '\n' +
          '让我们聊聊 `html`。',
          '### 8.  代码块\n' +
          '\n' +
          '使用 ``` 上下包裹表示代码块。\n' +
          '示例：\n' +
          '```' +
          '\n'+
          '这是一个代码块' +
          '\n'+
          '```',
          '### 9.支持LaTex\n' +
          '\n' +
          '用`$行内公式$`表示行内公式\n' +
          '用`$$段落公式$$`表示段落公式\n' +
          '\n' +
          '- 上标`^{}`， 下标`_{}`，如 $x^2, x_1^2$\n' +
          '- 分数`\\frac{分子}{分母}`,如 $\\frac{1}{x}$'
        ]
      }
    },
    filters: {
      rows(text) {
        return text.split("\n").length+1
      }
    }
  }
</script>

<style scoped>
  .tip {
    margin: .3rem;
    border-radius: .5em;
    border: .5em solid #eee;
  }
  .box {
    padding: 1em;
    background: #eee;
  }
  .tip textarea {
    width: 100%;
    outline: none;
    border: none;
    resize: none;
    background: #eee;
  }
  .tip .c-section {
    border: 1em solid #fff;
  }
</style>
