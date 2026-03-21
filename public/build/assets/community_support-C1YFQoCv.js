import{i as p}from"./telegram-init-COMxwxKX.js";document.addEventListener("DOMContentLoaded",()=>{p(),u()});function c(){const n=document.getElementById("chatInput"),t=n.value.trim();if(!t)return;const e=document.getElementById("chatMessages"),s=new Date,r=s.getHours()+":"+(s.getMinutes()<10?"0":"")+s.getMinutes(),o=document.createElement("div");o.className="flex gap-3 items-end justify-end",o.innerHTML=`<div class="bg-primary text-white rounded-[1.4rem] rounded-br-lg px-5 py-3 max-w-[80%]">
    <p class="text-[14px] leading-relaxed">${t}</p>
    <p class="text-[10px] opacity-70 mt-1">${r}</p>
  </div>`,e.appendChild(o),n.value="",e.scrollTop=e.scrollHeight,setTimeout(()=>{const i=document.createElement("div");i.className="flex gap-3 items-end",i.id="typing",i.innerHTML=`<div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
      <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
    </div>
    <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3">
      <p class="text-[14px] text-muted-foreground">Typing...</p>
    </div>`,e.appendChild(i),e.scrollTop=e.scrollHeight,setTimeout(()=>{e.removeChild(document.getElementById("typing"));const a=["I'll check that for you. Please continue monitoring and let me know if anything changes.","That sounds normal for your stage. Keep up with your daily vitals!","Great question! I'd recommend discussing this at your next checkup. For now, stay hydrated and rest well.","Thanks for the update. Everything seems on track. Keep taking your prenatal vitamins!"],l=a[Math.floor(Math.random()*a.length)],d=document.createElement("div");d.className="flex gap-3 items-end",d.innerHTML=`<div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
        <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
      </div>
      <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3 max-w-[80%]">
        <p class="text-[14px] leading-relaxed text-foreground">${l}</p>
        <p class="text-[10px] text-muted-foreground mt-1">${r}</p>
      </div>`,e.appendChild(d),e.scrollTop=e.scrollHeight},1500)},500)}function u(){const n=document.getElementById("chatInput");n&&n.addEventListener("keypress",t=>{t.key==="Enter"&&!t.shiftKey&&(t.preventDefault(),c())})}window.sendMessage=c;
