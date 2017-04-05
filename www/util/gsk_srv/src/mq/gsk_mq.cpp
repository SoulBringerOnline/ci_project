#include "zmq.h"
#include "oi_misc.h"

int main(int argc, char** argv)
{
    if (argc != 3)
    {
        printf("Usage: %s pub_port sub_port\n", argv[0]);
        return -1;
    }

    DaemonInit();
    void* context = zmq_ctx_new();

    int iSubPort = atoi(argv[1]);
    char szSubBind[128] = {0};
    snprintf(szSubBind, sizeof(szSubBind) - 1, "tcp://*:%d", iSubPort );
    void* xsub = zmq_socket(context, ZMQ_XSUB);
    zmq_bind(xsub, szSubBind);

    int iPubPort = atoi(argv[2]);
    char szPubBind[128] = {0};
    snprintf(szPubBind, sizeof(szPubBind) - 1, "tcp://*:%d", iPubPort );
    void* xpub = zmq_socket(context, ZMQ_XPUB);
    zmq_bind(xpub, szPubBind);


    while (true)
    {
        zmq_proxy(xsub, xpub, NULL);
    }

    zmq_close(xsub);
    zmq_close(xpub);
    zmq_ctx_destroy(context);

    return 0;
}
