!function(){"use strict";var e=function(e,t,n){var a=document.createElement("option");return a.value=t,a.text=n,e.appendChild(a),a};!function(t){var n=t.gateway,a=t.table,o=t.metaKey,i=t.tokens,r=t.defaultOptionText,u="_payment_method_meta["+n+"]["+a+"]["+o+"]",c=document.getElementById(u),d=i.some((function(e){return e.tokenId.toString()===c.value}));if(c&&"SELECT"!==c.tagName){var m=document.createElement("select");if(m.id=u,m.name=u,!d){var l=e(m,"",r);l.disabled=!0,l.selected=!0}i.forEach((function(t){e(m,t.tokenId,t.displayName)})),d&&(m.value=c.value),c.parentElement.insertBefore(m,c),c.remove()}}(wcpaySubscriptionEdit)}();