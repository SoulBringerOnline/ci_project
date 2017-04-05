{
    if( $1 == "#__MESSAGE_FILED__" )
    {
        if( $2 == "array" )
        {
            printf("%s %s %s %s", $1 , $2 , $3 , $4)
            space_len = 70 - length( $1 )  - length( $2 )  - length( $3 )  - length( $4 )
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            printf( " %s\n" , $5)
        }
        else
        {
            printf("%s %s %s", $1 , $2 , $3 )
            space_len = 70 - length( $1 )  - length( $2 )  - length( $3 )  
            for( i = 0 ; i < space_len; i++ )
            {
                printf( " " )
            }
            printf( " %s\n" , $4)
        }
    }
    else
    {
        print $0
    }
}
