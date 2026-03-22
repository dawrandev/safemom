import{i as p}from"./telegram-init-COMxwxKX.js";console.log("community_support.js loaded");document.addEventListener("DOMContentLoaded",()=>{console.log("community_support.js DOMContentLoaded"),p(),u()});function c(){const t=document.getElementById("chatInput"),n=t.value.trim();if(!n)return;const e=document.getElementById("chatMessages"),i=new Date,l=i.getHours()+":"+(i.getMinutes()<10?"0":"")+i.getMinutes(),o=document.createElement("div");o.className="flex gap-3 items-end justify-end",o.innerHTML=`<div class="bg-primary text-white rounded-[1.4rem] rounded-br-lg px-5 py-3 max-w-[80%]">
    <p class="text-[14px] leading-relaxed">${n}</p>
    <p class="text-[10px] opacity-70 mt-1">${l}</p>
  </div>`,e.appendChild(o),t.value="",e.scrollTop=e.scrollHeight,setTimeout(()=>{const s=document.createElement("div");s.className="flex gap-3 items-end",s.id="typing",s.innerHTML=`<div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
      <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
    </div>
    <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3">
      <p class="text-[14px] text-muted-foreground">Typing...</p>
    </div>`,e.appendChild(s),e.scrollTop=e.scrollHeight,setTimeout(()=>{e.removeChild(document.getElementById("typing"));const r=["I'll check that for you. Please continue monitoring and let me know if anything changes.","That sounds normal for your stage. Keep up with your daily vitals!","Great question! I'd recommend discussing this at your next checkup. For now, stay hydrated and rest well.","Thanks for the update. Everything seems on track. Keep taking your prenatal vitamins!"],a=r[Math.floor(Math.random()*r.length)],d=document.createElement("div");d.className="flex gap-3 items-end",d.innerHTML=`<div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
        <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
      </div>
      <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3 max-w-[80%]">
        <p class="text-[14px] leading-relaxed text-foreground">${a}</p>
        <p class="text-[10px] text-muted-foreground mt-1">${l}</p>
      </div>`,e.appendChild(d),e.scrollTop=e.scrollHeight},1500)},500)}function u(){const t=document.getElementById("chatInput"),n=document.getElementById("sendMessageBtn");t&&t.addEventListener("keypress",e=>{e.key==="Enter"&&!e.shiftKey&&(e.preventDefault(),c())}),n&&(n.addEventListener("click",c),console.log("sendMessageBtn click listener added"))}window.sendMessage=c;
