(function(){
    var factory = function (exports) {
        var lang = {
            name : "zh-tw",
            description : "開源在線Markdown編輯器<br/>Open source online Markdown editor.",
            tocTitle    : "目錄",
            toolbar     : {
                undo             : "還原（Ctrl+Z）",
                redo             : "重做（Ctrl+Y）",
                bold             : "粗體",
                del              : "刪除線",
                italic           : "斜體",
                quote            : "引用",
                ucwords          : "將所選的每個單詞首字母轉成大寫",
                uppercase        : "將所選文字轉成大寫",
                lowercase        : "將所選文字轉成小寫",
                h1               : "標題1",
                h2               : "標題2",
                h3               : "標題3",
                h4               : "標題4",
                h5               : "標題5",
                h6               : "標題6",
                "list-ul"        : "無序列表",
                "list-ol"        : "有序列表",
                hr               : "分隔線",
                link             : "連結",
                "reference-link" : "引用連結",
                image            : "圖片",
                code             : "行內程式碼",
                "preformatted-text" : "預格式文字段落 / 程式碼（縮排）",
                "code-block"     : "程式碼（多語言風格）",
                table            : "加入表格",
                datetime         : "日期時間",
                emoji            : "Emoji 表情",
                "html-entities"  : "HTML 實體字符",
                pagebreak        : "插入分頁符",
                watch            : "關閉即時預覽",
                unwatch          : "開啟即時預覽",
                preview          : "全窗口預覽HTML（按 Shift + ESC 退出）",
                fullscreen       : "全營幕（按 ESC 退出）",
                clear            : "清空",
                search           : "搜尋",
                help             : "使用幫助",
                info             : "關於" + exports.title
            },
            buttons : {
                enter  : "確定",
                cancel : "取消",
                close  : "關閉"
            },
            dialog : {
                link   : {
                    title    : "加入連結",
                    url      : "連結位置",
                    urlTitle : "連結標題",
                    urlEmpty : "錯誤：請填寫連結位置。"
                },
                referenceLink : {
                    title    : "加入引用連結",
                    name     : "引用名稱",
                    url      : "連結地址",
                    urlId    : "連結ID",
                    urlTitle : "連結標題",
                    nameEmpty: "錯誤：引用連結的名稱不能為空。",
                    idEmpty  : "錯誤：請填寫引用連結的ID。",
                    urlEmpty : "錯誤：請填寫引用連結的URL地址。"
                },
                image  : {
                    title    : "加入圖片",
                    url      : "圖片地址",
                    link     : "圖片連結",
                    alt      : "圖片描述",
                    uploadButton     : "本地上傳",
                    imageURLEmpty    : "錯誤：圖片地址不能為空。",
                    uploadFileEmpty  : "錯誤：上傳的圖片不能為空！",
                    formatNotAllowed : "錯誤：只允許上傳圖片檔案，允許上傳的圖片檔案格式有："
                },
                preformattedText : {
                    title             : "加入預格式文本或程式碼區塊", 
                    emptyAlert        : "錯誤：請填寫預格式文本或程式碼的內容。"
                },
                codeBlock : {
                    title             : "加入程式碼區塊",                 
                    selectLabel       : "程式語言：",
                    selectDefaultText : "請選擇程式碼語言",
                    otherLanguage     : "其他語言",
                    unselectedLanguageAlert : "錯誤：請選擇程式碼所屬的語言類型。",
                    codeEmptyAlert    : "錯誤：請填寫程式碼內容。"
                },
                htmlEntities : {
                    title : "HTML實體字符"
                },
                help : {
                    title : "使用幫助"
                }
            }
        };
        
        exports.defaults.lang = lang;
    };
    
	// CommonJS/Node.js
	if (typeof require === "function" && typeof exports === "object" && typeof module === "object")
    { 
        module.exports = factory;
    }
	else if (typeof define === "function")  // AMD/CMD/Sea.js
    {
		if (define.amd) { // for Require.js

			define(["editormd"], function(editormd) {
                factory(editormd);
            });

		} else { // for Sea.js
			define(function(require) {
                var editormd = require("../editormd");
                factory(editormd);
            });
		}
	} 
	else
	{
        factory(window.editormd);
	}
    
})();