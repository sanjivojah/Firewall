from netfilterqueue import NetfilterQueue
from scapy.layers.inet import IP
from scapy.layers.inet import TCP
from scapy.layers.inet import UDP
#from scapy.layers.inet import IMCP
import time
import json
import psycopg2
#from scapy.all import ICMP
from datetime import datetime
try:
    IPs = []
    PORTs = []
    ListOfAllowIpAddr=[]
    ListOfAllowPrefixes=[]
    ListOfAllowPorts=[]
    TimeThreshold = 10
    PacketThreshold = 100 
    conn = psycopg2.connect(
       database="firewall", user='postgres', password='test', host='localhost', port= '5432'
    )
    conn.autocommit = True
    cursor = conn.cursor()
    cursor.execute('''SELECT * from ip''')
    result = cursor.fetchall();
    length = len(result)
    for i in range(length):
        IPs.append(result[i][1])
        if(result[i][2]=='allow'): 
            ListOfAllowIpAddr.append(result[i][1])

    cursor.execute('''SELECT * from ports''')
    result1 = cursor.fetchall();
    length1 = len(result1)
    for i in range(length1):
        PORTs.append(result1[i][1])
        if(result1[i][2]=='allow'): 
            ListOfAllowPorts.append(result1[i][1])
    print(ListOfAllowPorts)
    cursor.execute('''SELECT * from prefix''')
    result2 = cursor.fetchall();
    length2 = len(result2)
    for i in range(length2):
        if(result2[i][2]=='allow'): 
            ListOfAllowPrefixes.append(result2[i][1])

except FileNotFoundError:
    print("Rule file (firewallrules.json) not found, setting default values")
    IPs = []
    PORTs = []
    ListOfAllowIpAddr=[]
    ListOfAllowPorts=[]
    ListOfAllowPrefixes=[]
    TimeThreshold = 10
    PacketThreshold = 100
    PacketThreshold = 100    
    BlockPingAttacks = True

def updatetime(traffic,tcp,udp):
    if traffic:
        cont=len(traffic)
        tcp1=len(tcp)
        udp1=len(udp)
        conn = psycopg2.connect(
        database="firewall", user='postgres', password='test', host='localhost', port= '5432'
        )   
        conn.autocommit = True
        cursor = conn.cursor()
        postgres_insert_query='''INSERT INTO traffic("time", count,tcp,udp) VALUES (%s,%s,%s,%s)'''
        record_to_insert = (datetime.now(), cont,tcp1,udp1)
        cursor.execute(postgres_insert_query, record_to_insert)
        #print('yo')
        a = datetime.now()
        traffic=[]
        conn.commit()
        conn.close()

a=datetime.now()
def counter():
    global a
    b = datetime.now()
    c=b-a
    val=round(c.seconds / 60,2)
    if(val>1):

        a=datetime.now()
        return 'yes'
    else:
        return 'no'

def firewall(pkt):
    sca = IP(pkt.get_payload())
    if(counter()=='yes'):
        updatetime(traffic,tcp,udp)
    else:
        traffic.append(sca.src)
        if(sca.haslayer(UDP)):
            g=sca.getlayer(UDP)
            udp.append(g.dport)
        if(sca.haslayer(TCP)):
            f=sca.getlayer(TCP)
            tcp.append(f.dport)
    
    if(sca.src in IPs):
        if(sca.src in ListOfAllowIpAddr):
            print(sca.src, "is a incoming IP address that is allowed by the firewall.")
        else:
            print(sca.src, "is a incoming IP address that is banned by the firewall.")
            pkt.drop()
            return 
            

        if(sca.haslayer(TCP)):
            t = sca.getlayer(TCP)
            if(t.dport in ListOfAllowPorts):

                if(t.dport in ListOfAllowPorts):
                    print(sca.src,":",t.dport, "address is allowed by the firewall.")
                else:
                    print(t.dport, "is a destination port that is blocked by the firewall.")
                    pkt.drop()
                    return
            else:
                pkt.drop()
                return
                

        if(sca.haslayer(UDP)):
            t = sca.getlayer(UDP)
            if(t.dport in ListOfAllowPorts):
                if(t.dport in ListOfAllowPorts):
                    print(sca.src,":",t.dport, "address is allowed by the firewall.")
                else:
                    print(t.dport, "is a destination port that is blocked by the firewall.")
                    pkt.drop()
                    return 
            else:
                pkt.drop()
                return 
                
        if(True in [sca.src.find(suff)==0 for suff in ListOfAllowPrefixes]):
            print("Prefix of " + sca.src + " is allowed by the firewall.")

        else:
            pkt.drop()
            return   
    else:
        pkt.drop()
        return

    # if(BlockPingAttacks and sca.haslayer(ICMP)): #attempt at preventing hping3
    #     t = sca.getlayer(ICMP)
    #     if(t.code==0):
    #         if(sca.src in DictOfPackets):
    #             temptime = list(DictOfPackets[sca.src])
    #             if(len(DictOfPackets[sca.src]) >= PacketThreshold):
    #                 if(time.time()-DictOfPackets[sca.src][0] <= TimeThreshold):
    #                     print("Ping by %s blocked by the firewall (too many requests in short span of time)." %(sca.src))
    #                     pkt.drop()
    #                     return
    #                 else:
    #                     DictOfPackets[sca.src].pop(0)
    #                     DictOfPackets[sca.src].append(time.time())
    #             else:
    #                 DictOfPackets[sca.src].append(time.time())
    #         else:
    #             DictOfPackets[sca.src] = [time.time()]

        #print("Packet from %s accepted and forwarded to IPTABLES" %(sca.src))      
        
        pkt.accept()
        return 
    
    pkt.accept()

nfqueue = NetfilterQueue()

traffic=[]
tcp=[]
udp=[]
updatetime(traffic,tcp,udp)

nfqueue.bind(1,firewall)
print("firewall Started")
try:
    nfqueue.run()
except KeyboardInterrupt:
    pass

nfqueue.unbind()